(ns Solution
    (:gen-class))

; Auto-generated code below aims at helping you parse
; the standard input according to the problem statement.
(defn abs [n] (max n (- n)))

(defn -main [& args]
    (let [n (read)]
        ; n: the number of temperatures to analyse
        (def tMin 9999)

        (loop [i n]
            (when (> i 0)
                  (let [t (read)]
                      (if (< (abs t) (abs tMin))
                          (def tMin t)
                          )

                      (if (= (abs t) (abs tMin))
                          (if (> t tMin)
                              (def tMin t)
                              )
                          )

                      (binding [*out* *err*] (println t))
                      ; t: a temperature expressed as an integer ranging from -273 to 5526
                      (recur (dec i))
                      )
                  )
            )

        ; (binding [*out* *err*]
        ;   (println "Debug messages..."))

        ; Write answer to stdout
        (if (= tMin 9999)
            (println 0)
            (println tMin)
        )
    )
)