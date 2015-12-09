STDOUT.sync = true # DO NOT REMOVE
# Auto-generated code below aims at helping you parse
# the standard input according to the problem statement.
# ---
# Hint: You can use the debug stream to print initialTX and initialTY, if Thor seems not follow your orders.

# lightX: the X position of the light of power
# lightY: the Y position of the light of power
# initialTX: Thor's starting X position
# initialTY: Thor's starting Y position
$lightX, $lightY, $initialTX, $initialTY = gets.split(" ").collect {|x| x.to_i}

$X = $initialTX
$Y = $initialTY
# game loop
loop do
    $remainingTurns = gets.to_i

    # Write an action using puts
    # To debug: STDERR.puts "Debug messages..."
    $move = '';

    if $Y < $lightY
        $move += 'S'
        $Y+=1

    elsif $Y > $lightY
        $move += 'N'
        $Y-=1
    end

    if $X < $lightX
        $move += 'E'
        $X+=1

    elsif $X > $lightX
        $move += 'W'
        $X-=1
    end

    puts $move # A single line providing the move to be made: N NE E SE S SW W or NW
end