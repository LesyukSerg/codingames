Title *

Table tennis calculation
Statement *

You and your friends decided to go to table tennis club. Some of your friends will come later and some went earlier.
Your goal is to calculate how much each person need to pay.
Input description *

<<Line 1:>> An integer [[T]] for the number of available tables.
<<Next [[N]] lines:>> Two space separated integers [[from]] and [[to]] for the time when table is available.
<<Next Line:>> An integer [[price]] for the price of one hour game on one table.
<<Next Line:>> An integer [[P]] for the number of players who will come.
<<Next [[P]] lines:>> Each line contains [[name]] - player's name, [[timeFrom]] - time when player will come, [[timeTo]] - time when player will went, separated by space

Output description *
<<[[P]] lines:>> Each line must contain [[name]] and [[money]] - he'll need to pay

Test cases *
                    Test 1

                    3
                    1830 2000
                    1930 2100
                    1830 2100
                    60
                    7
                    Латуха_Дмитрий 1830 2100
                    Лейбов_Евгений 1830 2000
                    Лесюк_Сергей 1830 2100
                    Просто_Эдик 1830 2100
                    Сергей_Кориненко 1900 2100
                    Сергей_Лебедев 1900 2100
                    Стеценко_Дмитрий 1830 1930

                    Латуха_Дмитрий 60
                    Лейбов_Евгений 36
                    Лесюк_Сергей 60
                    Просто_Эдик 60
                    Сергей_Кориненко 48
                    Сергей_Лебедев 48
                    Стеценко_Дмитрий  21
                    Validator 1

                    Input

                    Output
                    Test 2

                    Input

                    Output
                    Validator 2

                    Input

                    Output
                    Test 3

                    Input

                    Output
                    Validator 3

                    Input

                    Output
                    Test 4

                    Input

                    Output
                    Validator 4

                    Input

                    Output
                    ADD A TEST CASE
                    Solution language *

                    Solution *


                    Stub generator input *


                    Preview - Stub generator language

                    Preview - Generated stub


                    read T:int
                    loop T read tableTime:string(1024)
                    read price:int
                    read P:int
                    loop P read player:string(1024)