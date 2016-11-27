<?php

    class Game
    {
        public $player1, $player2, $red_chess_1,$red_chess_2,$blue_chess_1,$blue_chess_2,$null_chess_1;
    }
    class Chess
    {
        public $html_tag,$chess_pos,$id,$colour;
        public function Chess($html_tag, $chess_pos, $id, $colour)
        {
            $this->html_tag=$html_tag;
            $this->chess_pos=$chess_pos;
            $this->id=$id;
            $this->colour=$colour;
        }
    }
    $red_chess_1;
    $red_chess_2;
    $blue_chess_1;
    $blue_chess_2;
    $null_chess_1;
    $chesses;
    $pos_occupy = array();
    $player_side;
    $file_name;

    function chess_creation()
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side,$file_name;
        $game_file = fopen($file_name, "w");
        $red_chess_1 = new Chess("", 1, 1, "red");
        $red_chess_2 = new Chess("", 4, 2, "red");
        $blue_chess_1 = new Chess("", 2, 3, "blue");
        $blue_chess_2 = new Chess("", 5, 4, "blue");
        $null_chess_1 = new Chess("", 3, 0, "white");
        $pos_occupy = array(0=>0,1=>1, 2=>3, 3=>0,4=>2,5=>4);
        $player_side = 1;
        $chesses = array("red_chess_1"=>$red_chess_1,"red_chess_2"=>$red_chess_2,"blue_chess_1"=>$blue_chess_1,"blue_chess_2"=>$blue_chess_2,"null_chess_1"=>$null_chess_1,"pos_occupy"=>$pos_occupy,"player_side"=>$player_side);
        $json = json_encode($GLOBALS['chesses']);
        fwrite($game_file, $json);
        fclose($game_file);
    }

    function chess_import()
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side,$file_name;
        $game_file = fopen($file_name, "r");
        $json = fread($game_file, filesize($file_name));
        $chesses = json_decode($json);
        $red_chess_1 = new Chess("", $chesses->red_chess_1->chess_pos, $chesses->red_chess_1->id, $chesses->red_chess_1->colour);
        $red_chess_2 = new Chess("", $chesses->red_chess_2->chess_pos, $chesses->red_chess_2->id, $chesses->red_chess_2->colour);
        $blue_chess_1 = new Chess("", $chesses->blue_chess_1->chess_pos, $chesses->blue_chess_1->id, $chesses->blue_chess_1->colour);
        $blue_chess_2 = new Chess("", $chesses->blue_chess_2->chess_pos, $chesses->blue_chess_2->id, $chesses->blue_chess_2->colour);
        $null_chess_1 = new Chess("", $chesses->null_chess_1->chess_pos, $chesses->null_chess_1->id, $chesses->null_chess_1->colour);
        $pos_occupy = $chesses->pos_occupy;
        $player_side = $chesses->player_side;
        fclose($game_file);
    }

    function chess_saving()
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side,$file_name;
        $chesses = array("red_chess_1"=>$red_chess_1,"red_chess_2"=>$red_chess_2,"blue_chess_1"=>$blue_chess_1,"blue_chess_2"=>$blue_chess_2,"null_chess_1"=>$null_chess_1,"pos_occupy"=>$pos_occupy,"player_side"=>$player_side);
        $game_file = fopen($file_name, "w");
        $json = json_encode($GLOBALS['chesses']);
        fwrite($game_file, $json);
        fclose($game_file);
    }

    function check_chess_occupy(&$chess)
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side;
        $changed = false;
        switch ($chess->chess_pos) {
            case '1':
                if ($pos_occupy[2]==0) {
                    chess_change_pos($chess, 2);
                    $changed = true;
                } elseif ($pos_occupy[3]==0) {
                    chess_change_pos($chess, 3);
                    $changed = true;
                } elseif ($pos_occupy[4]==0) {
                    chess_change_pos($chess, 4);
                    $changed = true;
                }
                break;
            case '2':
                if ($pos_occupy[1]==0) {
                    chess_change_pos($chess, 1);
                    $changed = true;
                } elseif ($pos_occupy[3]==0) {
                    chess_change_pos($chess, 3);
                    $changed = true;
                }
                break;
            case '3':
                if ($pos_occupy[1]==0) {
                    chess_change_pos($chess, 1);
                    $changed = true;
                } elseif ($pos_occupy[2]==0) {
                    chess_change_pos($chess, 2);
                    $changed = true;
                } elseif ($pos_occupy[4]==0) {
                    chess_change_pos($chess, 4);
                    $changed = true;
                } elseif ($pos_occupy[5]==0) {
                    chess_change_pos($chess, 5);
                    $changed = true;
                }
                break;
            case '4':
                if ($pos_occupy[1]==0) {
                    chess_change_pos($chess, 1);
                    $changed = true;
                } elseif ($pos_occupy[3]==0) {
                    chess_change_pos($chess, 3);
                    $changed = true;
                } elseif ($pos_occupy[5]==0) {
                    chess_change_pos($chess, 5);
                    $changed = true;
                }
                break;
            case '5':
                if ($pos_occupy[3]==0) {
                    chess_change_pos($chess, 3);
                    $changed = true;
                } elseif ($pos_occupy[4]==0) {
                    chess_change_pos($chess, 4);
                    $changed = true;
                }
                break;



            default:
                # code...
                break;
        }
        if ($changed) {
            if ($player_side==1) {
                $player_side=2;
            } elseif ($player_side==2) {
                $player_side=1;
            }
        }
        chess_saving();
    }

    function chess_change_pos(&$chess, $pos)
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side;
        $pos_occupy[$chess->chess_pos] = $null_chess_1->id;
        $pos_occupy[$pos] = $chess->id;
        $null_chess_1->chess_pos = $chess->chess_pos;
        $chess->chess_pos = $pos;
    }

    function chess_clicked($id)
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side;
        switch ($id) {
            case '1':
                if ($player_side==1) {
                    check_chess_occupy($red_chess_1);
                }
                break;
            case '2':
                if ($player_side==1) {
                    check_chess_occupy($red_chess_2);
                }
                # code...
                break;
            case '3':
                if ($player_side==2) {
                    check_chess_occupy($blue_chess_1);
                }
                # code...
                break;
            case '4':
                if ($player_side==2) {
                    check_chess_occupy($blue_chess_2);
                }

                # code...
                break;
            default:
                echo "Error!";
                break;
        }
    }

    function get_position()
    {
        global $red_chess_1, $red_chess_2, $blue_chess_1, $blue_chess_2, $null_chess_1, $chesses,$pos_occupy,$player_side;
        check_win();
        $temp = array("pos_occupy"=>$pos_occupy,"player_side"=>$player_side);
        $json = json_encode($temp);
        echo $json;


        // echo $pos_occupy;
    }

    function check_win()
    {
        global $pos_occupy, $player_side;
        if ((($pos_occupy[1]==1)||($pos_occupy[1]==2))&&(($pos_occupy[2]==1)||($pos_occupy[2]==2))) {
            if ((($pos_occupy[3]==3)||($pos_occupy[3]==4))&&(($pos_occupy[4]==3)||($pos_occupy[4]==4))) {
                $player_side=4;
            }
        } elseif ((($pos_occupy[4]==1)||($pos_occupy[4]==2))&&(($pos_occupy[5]==1)||($pos_occupy[5]==2))) {
            if ((($pos_occupy[1]==3)||($pos_occupy[1]==4))&&(($pos_occupy[3]==3)||($pos_occupy[3]==4))) {
                $player_side=4;
            }
        } elseif ((($pos_occupy[1]==3)||($pos_occupy[1]==4))&&(($pos_occupy[2]==3)||($pos_occupy[2]==4))) {
            if ((($pos_occupy[3]==1)||($pos_occupy[3]==2))&&(($pos_occupy[4]==1)||($pos_occupy[4]==2))) {
                $player_side=3;
            }
        } elseif ((($pos_occupy[4]==3)||($pos_occupy[4]==4))&&(($pos_occupy[5]==3)||($pos_occupy[5]==4))) {
            if ((($pos_occupy[1]==1)||($pos_occupy[1]==2))&&(($pos_occupy[3]==1)||($pos_occupy[3]==2))) {
                $player_side=3;
            }
        }
    }

    function start($file){
        global $file_name;
        $file_name = $file.".json";
        if (file_exists($file_name)) {
            chess_import();
        } else {
            chess_creation();
        }
    }

    if (isset($_POST['action']) && !empty($_POST['action'])&&isset($_POST['gamefile']) && !empty($_POST['gamefile'])) {
        $action = $_POST['action'];
        // isset or something like that
        start($_POST['gamefile']);
        switch ($action) {
            case 'start':
                echo "Started!";
            break;
            case 'chess_clicked':
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $id = $_POST['id'];
                    chess_clicked($id);
                }
            break;
            case 'chess_creation':
                chess_creation();
            break;
            case 'get_chess_location':
                // if (isset($_POST['id']) && !empty($_POST['id'])) {

                    get_position();
                // }
            break;
            // ...etc...
        }
    }
