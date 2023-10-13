all: judge_client.cc judge_client.http
	g++ -Wall -c judge_client.cc
	g++ -Wall -o judge_client judge_client.o 
judge_client.http:judge_client.cc 
	g++ -o judge_client.http -O2 judge_client.cc
clean:
	rm judge_client judge_client.http
