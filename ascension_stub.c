//
// This is a very basic HTTP server that runs on port 80 and simulates successful ascension server requests.
// It *always* returns the exact same response to *any* request:
//
// {"error_code":100,"data":{"is_season_match":true}}
//
// That's all it does. This is enough to trick game server into thinking that everything is okay.
// Tested on Windows and Mac but should also work on Linux.
//
// Make sure you add '127.0.0.1 client.sea.heroesofnewerth.com' to /etc/hosts or C:\Windows\System32\drivers\etc\hosts
//

#include <stdio.h>
#ifdef _WIN32
#include <winsock.h>

#pragma comment(lib, "WSock32.Lib")

void print_error()
{
	int error = WSAGetLastError();
	char* s = NULL;
	FormatMessageA(FORMAT_MESSAGE_ALLOCATE_BUFFER | FORMAT_MESSAGE_FROM_SYSTEM | FORMAT_MESSAGE_IGNORE_INSERTS, 
	               NULL, error,
	               MAKELANGID(LANG_NEUTRAL, SUBLANG_DEFAULT),
	               (LPSTR)&s, 0, NULL);
	printf("errno %d / %s", error, s);
	LocalFree(s);
}

#else
#include <sys/socket.h>
#include <unistd.h>
#include <netinet/in.h>
#include <errno.h>
#include <string.h>

void closesocket(int sock) {
	close(sock);
}

void print_error()
{
	printf("errno %d / %s", errno, strerror(errno));
}
#endif

char REQUEST[10000];
char RESPONSE[] = "HTTP/1.1 200 OK\r\n"
			      "Content-Length: 50\r\n"
			      "Connection: close\r\n"
			      "\r\n"
			      "{\"error_code\":100,\"data\":{\"is_season_match\":true}}";

int main()
{
#ifdef _WIN32
	WSADATA wsaData;
	// Initialize Winsock
	int iResult = WSAStartup(MAKEWORD(2,2), &wsaData);
	if (iResult != 0) {
	    printf("WSAStartup failed: %d\n", iResult);
	    print_error();
	    return 1;
	}
#endif
	int server = socket(AF_INET, SOCK_STREAM, 0);
	if (server < 0) {
		perror("Could not create server socket");
		print_error();
		return -1;
	}

	static struct sockaddr_in server_addr;
	server_addr.sin_family = AF_INET;
	server_addr.sin_port = htons(80); // http port
	server_addr.sin_addr.s_addr = htonl(INADDR_ANY);

	char opt_val = 1;
	setsockopt(server, SOL_SOCKET, SO_REUSEADDR, &opt_val, sizeof(opt_val));

	int err = bind(server, (struct sockaddr*) &server_addr, sizeof(server_addr));
	if (err < 0) {
		perror("Could not bind server socket");
		print_error();
		return -1;
	}

	err = listen(server, SOMAXCONN);
	if (err < 0) {
		perror("Could not listen");
		print_error();
		return -1;
	}

	printf("Ascension server emulator started\n");
	fflush(stdout);

	fd_set rfds;
	for (;;) {
		int client = accept(server, NULL, NULL);
		if (client < 0) {
			perror("accept() failed");
			print_error();
			fflush(stdout);
		} else {
			// receive any amount of data. Even 1 byte is ok.
			int received = recv(client, REQUEST, sizeof(REQUEST), 0);

			// send the response.
			send(client, RESPONSE, sizeof(RESPONSE) - 1, 0);

			// dispose of the client.
			closesocket(client);
		}
	}
	return 0;
}
