var N = parseInt(readline());
var D = H = [];
for (var i = 0; i < N; i++) {
    H.push(parseInt(readline()));
}
H.sort();
N--;

for (i = 0; i < N; i++) {
    D.push(Math.abs(H[i] - H[i+1]));
}
var min = Math.min.apply(null, D);
//printErr(min);
print(min);

// To debug: printErr('Debug messages...');
