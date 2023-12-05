function funRecurs(R, L) {
    if (L > 1) {
        //printErr(R);
        let
            count = '',
            flag = '',
            rez = '';
        let line = R.split(" ");

        for (let i in line) {
            if (flag !== line[i]) {
                rez += count + ' ' + flag + ' ';

                flag = line[i];
                count = 0;
            }
            count++;
        }
        rez += count + ' ' + flag;
        rez = rez.trim();
        funRecurs(rez, --L);
    } else {
        print(R);
    }
}


let R = parseInt(readline()) + '';
let L = parseInt(readline());

funRecurs(R, L);
