(ns Player
    (:gen-class))

; Auto-generated code below aims at helping you parse
; the standard input according to the problem statement.
; ---
; Hint: You can use the debug stream to print initialTX and initialTY, if Thor seems not follow your orders.

(defn -main [& args]
    (let [LX (read) LY (read) tX (read) tY (read)]
        ; lightX: the X position of the light of power
        ; lightY: the Y position of the light of power
        ; initialTX: Thor's starting X position
        ; initialTY: Thor's starting Y position
        (def X tX)
        (def Y tY)
        (while true
            (let [remainingTurns (read)]
                (if (< Y LY)
                   (do (def Y (inc Y)) (print "S"))
                   (if (> Y LY)
                       (do (def Y (dec Y)) (print "N"))
                   )
                )

                (if (< X LX)
                   (do (def X (inc X)) (print "E"))
                   (if (> X LX)
                       (do (def X (dec X)) (print "W"))
                   )
                )

                (println "")
                ; remainingTurns: The remaining amount of turns Thor can move. Do not remove this line.
                ; (binding [*out* *err*] (println "Debug messages..."))

                ; A single line providing the move to be made: N NE E SE S SW W or NW
            )
        )
    )
)