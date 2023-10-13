all: judged.cc judged.http
	g++ -Wall -c judged.cc
	g++ -Wall -o judged judged.o

judged.http: judged.cc
	g++ -Wall -o judged.http judged.cc
