(ns Player
    (:gen-class))

; The while loop represents the game.
; Each iteration represents a turn of the game
; where you are given inputs (the heights of the mountains)
; and where you have to print an output (the index of the mountain to fire on)
; The inputs you are given are automatically updated according to your last actions.

(defn -main [& args]
    (def mMax 0)
    (def pos 0)
    (while true
        (loop [i 0]
            (when (< i 8)
                (let [mountainH (read)]
                    (when (> mountainH mMax)
                        (def mMax mountainH)
                        (def pos i)
                    )
                    ; mountainH: represents the height of one mountain.
                    (recur (inc i))
                )
            )
        )
        (println pos)
        (def mMax 0)
        ; (binding [*out* *err*]
        ;   (println "Debug messages..."))
        ; The index of the mountain to fire on.
    )
)