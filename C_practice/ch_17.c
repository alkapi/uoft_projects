#include <stdio.h>
#include <math.h>

int sum(int (*f)(int), int start, int end);

int main(void) {
	int sum = sum(pow, 0, 1);
	printf("sum is %d\n", sum);

}

int sum(int (*f)(int), int start, int end) {
	int i, sum = 0;
	for (i = 0; i < end + 1; i++) {
		sum += (*f)(i);
	}
	return sum;
}
