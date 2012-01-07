#include <stdio.h>
#include <stdlib.h>

struct node {
	int data;
	struct node *left;
	struct node *right;
	struct node *parent;
};

struct node *root = NULL;
struct node *root_t = NULL;
void bst_insert(int);
void insert_list(int);
struct node *bst_search(int);
void inorder(struct node *);
int is_bst(struct node *);

int main(void) {
	bst_insert(3);
	bst_insert(2);
	bst_insert(5);
	insert_list(3);
	insert_list(7);
	int isbst_t = is_bst(root_t);
	inorder(root);
	int isbst = is_bst(root);
	printf("is bst for root: %d\n", isbst);
	printf("is bst for root_t: %d\n", isbst_t); 
	struct node *_node = bst_search(3);
	printf("_node's data is %d\n", _node->data);
	return 0;
}

int is_bst(struct node *n) {
	if (n->left) {
		if (n->left->data > n->data)
			return 0;
		is_bst(n->left);
	}
	if (n->right) {
		if (n->right->data < n->data)
			return 0;
		is_bst(n->right);
	}
	return 1;
}

void insert_list(int item) {
	if (root_t == NULL) {
		if ((root_t = malloc(sizeof(struct node))) == NULL) {
			fprintf(stderr, "insert_tree: malloc failed\n"); 
			exit(1);
		}
		root_t->left = NULL;
		root_t->right = NULL;
		root_t->parent = NULL;
		root_t->data = item;
	} else {
		struct node *curr = root_t; 
		int found = 0;
		while (!found) {
			if (curr->left) {
				curr = curr->left;
			} else {
				found = 1;
				struct node *new;
				if ((new = malloc(sizeof(struct node))) == NULL) {
					fprintf(stderr, "insert_tree: malloc afiled\n");
					exit(1);
				}
				new->data = item;
				new->parent = curr;
				new->left = NULL;
				new->right = NULL;
				curr->left = new;
			}
		}
	}
}

struct node *bst_search(int item) {
	struct node *curr = root;
	while (curr) {
		if (item < curr->data)
			curr = curr->left;
		else if (item > curr->data)
			curr = curr->right;
		else
			return curr;
	}
	return NULL;
}

void bst_insert(int item) {
	if (root == NULL) {
		if ((root = malloc(sizeof(struct node))) == NULL) {
			fprintf(stderr, "Error: malloc failed in bst_insert\n");
			exit(EXIT_FAILURE);
		}
		root->data = item;
		root->left = NULL;
		root->right = NULL;
		root->parent = NULL;
	} else {
		int found = 0;
		struct node *curr = root;
		struct node *new_node;
		if ((new_node = malloc(sizeof(struct node))) == NULL) {
			fprintf(stderr, "Error: malloc failed in bst_insert\n");
			exit(EXIT_FAILURE);
		}	
		while (!found) {	
			if (item < curr->data) {
				if (curr->left) {
					curr = curr->left;
				} else {
					curr->left = new_node;
					curr->left->data = item;
					curr->left->parent = curr;
					curr->left->left = NULL;
					curr->left->right = NULL;
					found = 1;
				}
			} else {
				if (curr->right) {
					curr = curr->right;
				} else {
					curr->right = new_node;
					curr->right->data = item;
					curr->right->parent = curr;
					curr->right->left = NULL;
					curr->right->right = NULL;
					found = 1;
				}
			}
		}
	}
}

void inorder(struct node *curr) {
	if (curr->left)
		inorder(curr->left);
	printf("%d\n", curr->data);
	if (curr->right)
		inorder(curr->right);
}


