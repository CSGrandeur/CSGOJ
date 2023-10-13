/*
 * 
 *
 * This file is part of CSGOJ.
 *
 * You should have received a copy of the GNU General Public License
 * along with CSGOJ. if not, see <http://www.gnu.org/licenses/>.
 */
#include <sys/syscall.h>
#define HOJ_MAX_LIMIT -1
#define CALL_ARRAY_SIZE 512
#define LANG_C 0
#define LANG_CPP 1
#define LANG_PASCAL 2
#define LANG_JAVA 3
#define LANG_RUBY 4
#define LANG_BASH 5
#define LANG_PYTHON 6
#define LANG_PHP 7
#define LANG_PERL 8
#define LANG_CSHARP 9
#define LANG_OBJC 10
#define LANG_FREEBASIC 11
#define LANG_SCHEME 12
#define LANG_CLANG 13
#define LANG_CLANGPP 14
#define LANG_LUA 15
#define LANG_JS 16
#define LANG_GO 17
#define LANG_SQL 18
#define LANG_FORTRAN 19
#define LANG_MATLAB 20
#define LANG_COBOL 21

#ifdef __i386
   #include "okcalls32.h"
#endif
#ifdef __x86_64
   #include "okcalls64.h"
#endif
