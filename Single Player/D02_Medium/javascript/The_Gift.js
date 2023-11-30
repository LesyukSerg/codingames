let N = parseInt(readline());
let C = parseInt(readline());

let MONEY = [];
let CASH = [];

for (let i = 0; i < N; i++) {
    CASH.push(parseInt(readline()));
}

CASH = CASH.sort(function(a,b){return parseInt(a) - parseInt(b)});
//printErr(CASH);

for (let i in CASH) {
    if (C > 0) {
        part = Math.floor(C / (N - i));

        if (CASH[i] > part) {
            MONEY.push(part);
            C -= part;
        } else {
            MONEY.push(CASH[i]);
            C -= CASH[i];
        }
    } else {
        MONEY.push("0");
    }
}

if (C === 0) {
    print(MONEY.join("\n"));
} else {
    print('IMPOSSIBLE');
}

// Write an action using print()
// To debug: printErr('Debug messages...');
