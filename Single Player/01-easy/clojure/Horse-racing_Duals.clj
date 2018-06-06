(ns Solution
    (:gen-class))

; Auto-generated code below aims at helping you parse
; the standard input according to the problem statement.

(defn -main [& args]
    (let [horses (sort (doall (repeatedly (read) read)))]
        (let [horses2 (next horses)]
            (println (reduce min (map #(- %2 %1) horses horses2)))
        )
    )
)