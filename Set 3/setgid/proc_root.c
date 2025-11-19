#include <unistd.h>

int main(int argc, char *argv[]) {
    setuid(1001);
    execvp(argv[1], &argv[1]);
    return 1;
}