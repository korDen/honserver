#include <fcntl.h>
#include <io.h>
#include <stdio.h>
#include <direct.h>
#include <string.h>
#include <stdlib.h>

#include <set>
#include <iostream>
#include <fstream>
#include <string>
#include <unordered_map>
#include <sstream>
#include <Windows.h>

#include <miniz.h>
#include <pugixml.hpp>

int main() {
	// Open manifest.xml
	pugi::xml_document doc;
	pugi::xml_parse_result result = doc.load_file("manifest.xml");
	if (!result) {
		printf("Failed to parse manifest.xml: %s\n", result.description());
		return 0;
	}

	// E.g. game/resources0.s2z -> mz_zip_archive corresponding to the path.
	std::unordered_map<std::string, mz_zip_archive*> fileNameToZipArchive;

	pugi::xml_node manifest = doc.first_child();
	std::set<std::string> originalFiles;
	for (pugi::xml_node file = manifest.first_child(); file; file = file.next_sibling()) {
		// extract file path (e.g. game/resources0.s2z/ui/alt_info.interface)
		std::string path = file.attribute("path").as_string();
		const char* checksumHex = file.attribute("checksum").as_string();
		unsigned long checksum = ~strtoul(checksumHex, nullptr, 16);

		// check if it's supposed to be stored in s2z
		size_t index = path.find(".s2z/");
		if (index != std::string::npos) {
			// extract path (e.g. game/resources0.s2z/ui/alt_info.interface -> ui/alt_info.interface)
			std::string archivePath = path.substr(0, index + 4); 
			size_t slashIndex = archivePath.find_last_of('/');
			std::string relativePath = path.substr(index + 5);

			mz_zip_archive* zipArchive = (mz_zip_archive*)malloc(sizeof(mz_zip_archive));
			mz_zip_zero_struct(zipArchive);

			auto it = fileNameToZipArchive.find(archivePath);
			if (it == fileNameToZipArchive.end()) {
				// not found, load it.
				if (!mz_zip_reader_init_file(zipArchive, archivePath.c_str(), 0)) {
					printf("error opening s2z\n");
					exit(0);
				}

				fileNameToZipArchive[archivePath] = zipArchive;
			}
			else {
				zipArchive = it->second;
			}

			// verify CRC32.
			int index = mz_zip_reader_locate_file(zipArchive, relativePath.c_str(), nullptr, 0);
			if (index < 0) {
				printf("error locating file in s2z\n");
				exit(0);
			}

			mz_zip_archive_file_stat fileStat;
			memset(&fileStat, 0, sizeof(fileStat));
			mz_zip_reader_file_stat(zipArchive, index, &fileStat);
			if (fileStat.m_crc32 != checksum) {
				printf("checksum failed");
				exit(0);
			}
		} else {
			// File is NOT stored in s2z.
			std::ifstream fileOnDisk(path, std::ios::binary | std::ios::ate);
			std::streamoff size = fileOnDisk.tellg();
			fileOnDisk.seekg(0, std::ios::beg);
			std::vector<char> buffer;
			buffer.resize(size);
			fileOnDisk.read(buffer.data(), size);

			long fileChecksum = mz_crc32(0, (unsigned char*) & buffer[0], buffer.size());
			if (fileChecksum != checksum) {
				printf("checksum mismatch");
			}
		}
	}

	printf("Done.\n");
	return 0;
}
