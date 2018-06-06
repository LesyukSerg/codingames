var L = parseInt(readline());
var H = parseInt(readline());
var Text = readline();
var alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

Text = Text.toUpperCase();

for (var i = 0; i < H; i++) {

    var LINE = readline();
    var Rez = '';

    for (var k=0; k < Text.length; k++) {
        var pos = alpha.indexOf(Text[k]);

        if(pos === false) pos = 26;

        Rez += LINE.substr(pos*L, L);
    }

    print(Rez);
}