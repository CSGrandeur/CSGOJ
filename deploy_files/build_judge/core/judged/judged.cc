// File:   judged.cc
// Author: sempr, zhblue
// refacted by CSGrandeur
/*
 * Copyright 2008 sempr <iamsempr@gmail.com>, zhblue<newsclan@gmail.com>
 *
 * Refacted and modified by CSGrandeur<csgrandeur@gmail.com>
 *
 *
 * This file is part of CSGOJ.
 
 * You should have received a copy of the GNU General Public License
 * along with CSGOJ. if not, see <http://www.gnu.org/licenses/>.
 */
#include <time.h>
#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdlib.h>
#include <unistd.h>
#include <syslog.h>
#include <errno.h>
#include <fcntl.h>
#include <stdarg.h>
#include <sys/wait.h>
#include <sys/stat.h>
#include <signal.h>
#include <sys/resource.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>

#define BUFFER_SIZE 1024
#define LOCKFILE "/var/run/judged.pid"
#define LOCKMODE (S_IRUSR|S_IWUSR|S_IRGRP|S_IROTH)
#define STD_MB 1048576LL

#define OJ_WT0 0
#define OJ_WT1 1
#define OJ_CI 2
#define OJ_RI 3
#define OJ_AC 4
#define OJ_PE 5
#define OJ_WA 6
#define OJ_TL 7
#define OJ_ML 8
#define OJ_OL 9
#define OJ_RE 10
#define OJ_CE 11
#define OJ_CO 12
static char lock_file[BUFFER_SIZE+32]=LOCKFILE;
static char oj_home[BUFFER_SIZE];
static char oj_lang_set[BUFFER_SIZE];
static int port_number;
static int max_running;
static int sleep_time;
static int sleep_tmp;
static int oj_tot;
static int oj_mod;
static int http_judge = 0;
static char http_baseurl[BUFFER_SIZE];
static char http_apipath[BUFFER_SIZE];
static char http_loginpath[BUFFER_SIZE];
static char http_username[BUFFER_SIZE];
static char http_password[BUFFER_SIZE];
static int prefetch=80;

static int turbo_mode = 0;
static int oj_dedicated=0;

static bool STOP = false;
static int DEBUG = 0;
static int ONCE = 0;


void call_for_exit(int s) {
	if(DEBUG){
		STOP = true;
		printf("Stopping judged...\n");
	}else{
		printf("CSGOJ Refusing to stop...\n Please use kill -9 !\n");
	}
}

void write_log(const char *fmt, ...) {
	va_list ap;
	char buffer[4096];
//	time_t          t = time(NULL);
//	int             l;
	sprintf(buffer, "%s/log/client.log", oj_home);
	FILE *fp = fopen(buffer, "ae+");
	if (fp == NULL) {
		fprintf(stderr, "openfile error!\n");
		system("pwd");
	}
	va_start(ap, fmt);
	vsprintf(buffer, fmt, ap);
	fprintf(fp, "%s\n", buffer);
	if (DEBUG)
		printf("%s\n", buffer);
	va_end(ap);
	fclose(fp);

}

int after_equal(char * c) {
	int i = 0;
	for (; c[i] != '\0' && c[i] != '='; i++)
		;
	return ++i;
}
void trim(char * c) {
	char buf[BUFFER_SIZE];
	char * start, *end;
	strcpy(buf, c);
	start = buf;
	while (isspace(*start))
		start++;
	end = start;
	while (!isspace(*end))
		end++;
	*end = '\0';
	strcpy(c, start);
}
bool read_buf(char * buf, const char * key, char * value) {
	if (strncmp(buf, key, strlen(key)) == 0) {
		strcpy(value, buf + after_equal(buf));
		trim(value);
		if (DEBUG)
			printf("%s\n", value);
		return 1;
	}
	return 0;
}
void read_int(char * buf, const char * key, int * value) {
	char buf2[BUFFER_SIZE];
	if (read_buf(buf, key, buf2))
		sscanf(buf2, "%d", value);

}
// read the configue file
void init_judge_conf() {
	FILE *fp = NULL;
	char buf[BUFFER_SIZE];
	max_running = 3;
	sleep_time = 1;
	oj_tot = 1;
	oj_mod = 0;
	strcpy(oj_lang_set, "0,1,3,6");
	fp = fopen("./etc/judge.conf", "r");
	if (fp != NULL) {
		while (fgets(buf, BUFFER_SIZE - 1, fp)) {
			read_int(buf, "OJ_RUNNING", &max_running);
			read_int(buf, "OJ_SLEEP_TIME", &sleep_time);
			read_int(buf, "OJ_TOTAL", &oj_tot);
			read_int(buf, "OJ_DEDICATED", &oj_dedicated);
			
			read_int(buf, "OJ_MOD", &oj_mod);

			read_int(buf, "OJ_HTTP_JUDGE", &http_judge);
			read_buf(buf, "OJ_HTTP_BASEURL", http_baseurl);
			read_buf(buf, "OJ_HTTP_APIPATH", http_apipath);
			read_buf(buf, "OJ_HTTP_LOGINPATH", http_loginpath);
			read_buf(buf, "OJ_HTTP_USERNAME", http_username);
			read_buf(buf, "OJ_HTTP_PASSWORD", http_password);
			read_buf(buf, "OJ_LANG_SET", oj_lang_set);

		}

		sleep_tmp = sleep_time;
			fclose(fp);
	}
}

void run_client(int runid, int clientid) {
	char buf[BUFFER_SIZE], runidstr[BUFFER_SIZE];
	struct rlimit LIM;
	LIM.rlim_max = 800;
	LIM.rlim_cur = 800;
	setrlimit(RLIMIT_CPU, &LIM);

	LIM.rlim_max = 1024 * STD_MB;
	LIM.rlim_cur = 1024 * STD_MB;
	setrlimit(RLIMIT_FSIZE, &LIM);
#ifdef __mips__
	LIM.rlim_max = STD_MB << 12;
	LIM.rlim_cur = STD_MB << 12;
#endif
#ifdef __arm__
	LIM.rlim_max = STD_MB << 11;
	LIM.rlim_cur = STD_MB << 11;
#endif
#ifdef __aarch64__
	LIM.rlim_max = STD_MB << 15;
	LIM.rlim_cur = STD_MB << 15;
#endif
#ifdef __i386
	LIM.rlim_max = STD_MB << 11;
	LIM.rlim_cur = STD_MB << 11;
#endif
#ifdef __x86_64__
	LIM.rlim_max = STD_MB << 15;
	LIM.rlim_cur = STD_MB << 15;
#endif
	setrlimit(RLIMIT_AS, &LIM);

	LIM.rlim_cur = LIM.rlim_max = 800* max_running;
	setrlimit(RLIMIT_NPROC, &LIM);

	//buf[0]=clientid+'0'; buf[1]=0;
	sprintf(runidstr, "%d", runid);
	sprintf(buf, "%d", clientid);

	if(DEBUG) {
		printf("---CLIENT START FOR %s AT %s\n", runidstr, buf);
    	execl("/usr/bin/judge_client", "/usr/bin/judge_client", runidstr, buf, oj_home, "debug", (char *) NULL);
	} else {
		execl("/usr/bin/judge_client", "/usr/bin/judge_client", runidstr, buf, oj_home, (char *) NULL);
	}

}
FILE * read_cmd_output(const char * fmt, ...) {
	char cmd[BUFFER_SIZE*2];

	FILE * ret = NULL;
	va_list ap;

	va_start(ap, fmt);
	vsprintf(cmd, fmt, ap);
	va_end(ap);
	//if(DEBUG) printf("%s\n",cmd);
	ret = popen(cmd, "r");

	return ret;
}
int read_int_http(FILE * f) {
	char buf[BUFFER_SIZE];
	fgets(buf, BUFFER_SIZE - 1, f);
	return atoi(buf);
}
bool check_login() {
	const char * cmd =
			"wget --post-data=\"checklogin=1\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s%s\"";
	int ret = 0;

	FILE * fjobs = read_cmd_output(cmd, http_baseurl, http_apipath);
	ret = read_int_http(fjobs);
	pclose(fjobs);

	return ret > 0;
}
void login() {
	if (!check_login()) {
		char cmd[BUFFER_SIZE*5];
		sprintf(cmd,
				"wget --post-data=\"user_id=%s&password=%s\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s%s\"",
				http_username, http_password, http_baseurl, http_loginpath);
		system(cmd);
	}

}
int _get_jobs_http(int * jobs) {
	login();
	int ret = 0;
	int i = 0;
	char buf[BUFFER_SIZE];
	const char * cmd =
			"wget --post-data=\"getpending=1&oj_lang_set=%s&max_running=%d\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s%s\"";
	FILE * fjobs = read_cmd_output(cmd, oj_lang_set, max_running, http_baseurl, http_apipath);
	while (fscanf(fjobs, "%s", buf) != EOF) {
		//puts(buf);
		int sid = atoi(buf);
		if (sid > 0)
			jobs[i++] = sid;
		//i++;
	}
	pclose(fjobs);
	ret = i;
	while (i <= max_running * prefetch)
		jobs[i++] = 0;
	return ret;
}

int get_jobs(int * jobs) {
	return _get_jobs_http(jobs);
}


bool _check_out_http(int solution_id, int result) {
	login();
	const char * cmd =
			"wget --post-data=\"checkout=1&sid=%d&result=%d\" --load-cookies=cookie --save-cookies=cookie --keep-session-cookies -q -O - \"%s%s\"";
	int ret = 0;
	FILE * fjobs = read_cmd_output(cmd, solution_id, result, http_baseurl, http_apipath);
	fscanf(fjobs, "%d", &ret);
	pclose(fjobs);

	return ret;
}
bool check_out(int solution_id, int result) {
	return _check_out_http(solution_id, result);	// HTTP_JUDGE 多机判题默认分布式，不需要 OJ_TOTAL 参数
}
static int workcnt = 0;
int work() {
//      char buf[1024];
	static int error=0;   // 出错计数器
	int retcnt = 0;
	int i = 0;
	static pid_t ID[100];
	int runid = 0;
	int jobs[max_running * prefetch + 1];
	pid_t tmp_pid = 0;

	//for(i=0;i<max_running;i++){
	//      ID[i]=0;
	//}
	for(i=0;i<max_running *prefetch +1 ;i++)
		jobs[i]=0;

	//sleep_time=sleep_tmp;
	/* get the database info */
	if (!get_jobs(jobs)){
		return 0;
	}
	/* exec the submit */
	for (int j = 0; jobs[j] > 0; j++) {
		runid = jobs[j];
		if (runid % oj_tot != oj_mod)
			continue;
		if (workcnt >= max_running) {           // if no more client can running
			tmp_pid = waitpid(-1, NULL, WNOHANG);     // wait for one child exit
			if (DEBUG) printf("try get one tmp_pid=%d\n",tmp_pid);
			for (i = 0; i < max_running; i++){     // get the client id
				if (ID[i] == tmp_pid){
					workcnt--;
					retcnt++;
					ID[i] = 0;
					break; // got the client id
				}
			}

		} else {                                             // have free client

			for (i = 0; i < max_running; i++)     // find the client id
				if (ID[i] == 0){
					break;    // got the client id
				}
		}
		if(i<max_running){
			if (workcnt < max_running && check_out(runid, OJ_CI)) {
				workcnt++;
				ID[i] = fork();                                   // start to fork
				if (ID[i] == 0) {

					if (DEBUG){
						write_log("Judging solution %d", runid);
						write_log("<<=sid=%d===clientid=%d==>>\n", runid, i);
					}
					run_client(runid, i);    // if the process is the son, run it
					workcnt--;
					exit(0);
				}

			} else {
			//	ID[i] = 0;
				if(DEBUG){
					if(workcnt<max_running)
						printf("check out failure ! runid:%d pid:%d \n",i,ID[i]);
					else
						printf("workcnt:%d max_running:%d ! \n",workcnt,max_running);
						
				}
				usleep(5000);
			}
		}
		if(DEBUG) {
			  printf("workcnt:%d max_running:%d ! \n",workcnt,max_running);
		}

	}
	int NOHANG=0;
	if(oj_dedicated && (rand()%100>20) ) NOHANG=WNOHANG;    // CPU 占用大约80%左右，不要打满
	while ((tmp_pid = waitpid(-1, NULL, NOHANG )) > 0) {       // if run dedicated judge using WNOHANG
		for (i = 0; i < max_running; i++){     // get the client id
			if (ID[i] == tmp_pid){
			
				workcnt--;
				retcnt++;
				ID[i] = 0;
				break; // got the client id
			}
		}
		printf("tmp_pid = %d\n", tmp_pid);
	}

	if (DEBUG && retcnt)
		write_log("<<%ddone!>>", retcnt);
	//free(ID);
	//free(jobs);
	return retcnt;
}

int lockfile(int fd) {
	struct flock fl;
	fl.l_type = F_WRLCK;
	fl.l_start = 0;
	fl.l_whence = SEEK_SET;
	fl.l_len = 0;
	return (fcntl(fd, F_SETLK, &fl));
}

int already_running() {
	int fd;
	char buf[16];
	fd = open(lock_file, O_RDWR | O_CREAT, LOCKMODE);
	if (fd < 0) {
		syslog(LOG_ERR | LOG_DAEMON, "can't open %s: %s", LOCKFILE,
				strerror(errno));
		exit(1);
	}
	if (lockfile(fd) < 0) {
		if (errno == EACCES || errno == EAGAIN) {
			close(fd);
			return 1;
		}
		syslog(LOG_ERR | LOG_DAEMON, "can't lock %s: %s", LOCKFILE,
				strerror(errno));
		exit(1);
	}
	ftruncate(fd, 0);
	sprintf(buf, "%d", getpid());
	write(fd, buf, strlen(buf) + 1);
	return (0);
}
int daemon_init(void)

{
	pid_t pid;

	if ((pid = fork()) < 0)
		return (-1);

	else if (pid != 0)
		exit(0); /* parent exit */

	/* child continues */

	setsid(); /* become session leader */

	chdir(oj_home); /* change working directory */

	umask(0); /* clear file mode creation mask */

	close(0); /* close stdin */
	close(1); /* close stdout */
	
	close(2); /* close stderr */
	
	int fd = open( "/dev/null", O_RDWR );
	dup2( fd, 0 );
	dup2( fd, 1 );
	dup2( fd, 2 );
	if ( fd > 2 ){
		close( fd );
	}

	return (0);
}

int main(int argc, char** argv) {
	DEBUG = (argc > 2);
	ONCE = (argc > 3);
	if (argc > 1)
		strcpy(oj_home, argv[1]);
	else
		strcpy(oj_home, "/home/judge");
	chdir(oj_home);    // change the dir

	sprintf(lock_file,"%s/etc/judge.pid",oj_home);
	if (!DEBUG)
		daemon_init();
	if ( already_running()) {
		syslog(LOG_ERR | LOG_DAEMON,
				"This daemon program is already running!\n");
		printf("%s already has one judged on it!\n",oj_home);
		return 1;
	}
	if(!DEBUG)
		system("/sbin/iptables -A OUTPUT -m owner --uid-owner judge -j DROP");
//	struct timespec final_sleep;
//	final_sleep.tv_sec=0;
//	final_sleep.tv_nsec=500000000;
	init_judge_conf();	// set the database info

	signal(SIGQUIT, call_for_exit);
	signal(SIGINT, call_for_exit);
	signal(SIGTERM, call_for_exit);
	int j = 1;
	int n = 0;
	while (!STOP) {			// start to run until call for exit
		n=0;
		while (j && http_judge) {

			j = work();
			n+=j;
			if(turbo_mode==2&&(n>max_running*10||j<max_running)){
				n=0;
			}

			if(ONCE && j==0) break;
		}
		if(ONCE && j==0) break;
        if(n==0){
            printf("workcnt:%d\n",workcnt);
            sleep(sleep_time);
            if(DEBUG) printf("sleeping ... %ds \n",sleep_time);
        }
		j = 1;
	}

	return 0;
}
