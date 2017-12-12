(ns Player
    (:gen-class))

; Auto-generated code below aims at helping you parse
; the standard input according to the problem statement.

(defn -main [& args]
    (let [surfaceN (read)]
        ; surfaceN: the number of points used to draw the surface of Mars.
        (loop [i surfaceN]
            (when (> i 0)
                  (let [landX (read) landY (read)]
                      ; landX: X coordinate of a surface point. (0 to 6999)
                      ; landY: Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.
                      (recur (dec i))
                      )
                  )
            )
        (def flag 0)
        (while true
            (let [
                    X      (read)
                    Y      (read)
                    hSpeed (read)
                    vSpeed (read)
                    fuel   (read)
                    rotate (read)
                    power  (read)]
               ; hSpeed: the horizontal speed (in m/s), can be negative.
               ; vSpeed: the vertical speed (in m/s), can be negative.
               ; fuel: the quantity of remaining fuel in liters.
               ; rotate: the rotation angle in degrees (-90 to 90).
               ; power: the thrust power (0 to 4).

               ; (binding [*out* *err*] (println "Debug messages..."))
                (if (< vSpeed -44)
                    (def flag (inc flag))
                )
                (binding [*out* *err*] (println flag))

                (if (> flag 0)
                    (println "0 4")
                    (println "0 0")
                )
                ; 2 integers: rotate power. rotate is the desired rotation angle (should be 0 for level 1), power is the desired thrust power (0 to 4).
            )
        )
    )
)