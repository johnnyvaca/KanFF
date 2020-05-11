<?php
/**
 *  Project: KanFF
 *  File: generationData.php file to generate data for fill the database with the tables of the MCD v1.1
 *  Author: Samuel Roland
 *  Creation date: 08.05.2020
 */

function getRandomDateFormated($start = 1546300800)
{
    //Generate a date between 01.01.2019 and today formated in DATETIME format.
    return date("Y-m-d H:i:s", rand($start, time()));
}

function dataUsers()
{
    //Get all users basic data generated with generatedata.com (firstname, lastname, bio)
    $usersressources = json_decode(file_get_contents("data-ressources/basic-data-users.json"), true);

    echo "\n-----------------------------\n Generating Users \n-----------------------------\n ";
    $id = 0;
    $users = [];    //array for the users generated

    //For each user generate the other data
    foreach ($usersressources as $ressource) {
        $id += 1;
        $userinrun = $ressource;
        //Truncate the biography field if longer than 500 chars
        if (strlen($userinrun['biography']) > 500) {
            $userinrun['biography'] = substr($userinrun['biography'], 0, 500);
        }
        //Take the names as normal
        $firstname = $userinrun['firstname'];
        $lastname = $userinrun['lastname'];

        //Generate username with firstname and a number after
        $username = strtolower($firstname) . rand(10, 99);
        //Generate initials
        $initials = substr($firstname, 0, 1) . substr($lastname, 0, 1) . substr($lastname, strlen($lastname) - 1);
        $initials = strtoupper($initials);
        //half the time, email is set to "firstname.lastname@example.com" and if not the email is null
        if (rand(0, 1)) {
            $email = strtolower($firstname) . "." . strtolower($lastname) . "@example.com";
        } else {
            $email = null;
        }

        //Generate phonenumber of 10 number
        $phonenumber = rand(100, 999) . rand(100, 999) . rand(10, 99) . rand(10, 99);

        //Generate a password with the firstname hashed
        $password = password_hash($firstname, PASSWORD_DEFAULT);

        //Generate inscription date,
        $inscription = getRandomDateFormated();

        //Status is by default 0, so yyyyy
        $status = 0;

        //Save data not already present in $userinrun
        $userinrun['id'] = $id; //fix id in prevision of foreign keys later
        $userinrun['email'] = $email;
        $userinrun['initials'] = $initials;
        $userinrun['username'] = $username;
        $userinrun['inscription'] = $inscription;
        $userinrun['status'] = $status;
        $userinrun['phonenumber'] = $phonenumber;
        $userinrun['password'] = $password;

        //Save the userinrun in the lists:
        $users[] = $userinrun;
        echo "\n" . $userinrun['id'] . " " . $userinrun['firstname'] . " " . $userinrun['lastname'] . " " . $userinrun['initials'] . " " . $userinrun['username'] . " " . $userinrun['email'] . " " . $userinrun['inscription'] . " " . $userinrun['status'] . " " . $userinrun['phonenumber'] . " " . $userinrun['password'];
    }
    var_dump(json_encode($users));
    file_put_contents("data_generated_general/users.json", json_encode($users, JSON_INVALID_UTF8_IGNORE | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function dataGroups()
{
    $groupsressources = json_decode(file_get_contents("data-ressources/basic-data-groups.json"), true);

    echo "\n-----------------------------\n Generating Groups \n-----------------------------\n ";
    $id = 0;
    $groups = [];    //array for the users generated

    //For each user generate the other data
    foreach ($groupsressources as $ressource) {
        $id += 1;
        $group = $ressource;

        //Half time, email is the last word of the name of the group with @assoc.ch
        if (rand(0, 1)) {
            $wordsOfName = explode(" ", $group['name']);
            $group['email'] = strtolower($wordsOfName[count($wordsOfName) - 1]) . "@assoc.ch";
        } else {
            //The other time it's null
            $group['email'] = null;
        }
        $group['image'] = uniqid("group_", true) . ".png";

        //Generating "visible" and "restrict_access". The groups are in most cases visible and not access restricted . just in 1/5 of cases the groups are considered sensitive so restric_access is 1 (true) and sometimes not visible.

        $group['visible'] = 1;  //visible by default

        //Generate parameters for sensitive groups in 1/5 of cases.
        if (rand(1, 5) == 1) {
            $group['restrict_access'] = 1;
            if (rand(0, 1)) {
                $group['visible'] = 0;
            }
        } else {
            $group['restrict_access'] = 0;
        }

        //Generate chat and drive link that seem like a real like
        $group['chat_link'] = "chat.link/join?v=" . generateRandomString(rand(10, 15));
        $group['drive_link'] = "drive.link/open?f=" . generateRandomString(rand(50, 70));

        //Foreign key of the user creator
        $group['creator_id'] = rand(1, 100);

        $group['creation_date'] = getRandomDateFormated();

        print_r("\n" . $group['name']);
        print_r("\n" . $group['email'] . "\n");
    }

}

function data_user_join_group()
{
    $joins = [];
    $id = 0;
    //For the 100 users
    for ($i = 1; $i <= 100; $i++) {
        $join['user_id'] = $i;



        //Generate for the most majority of users but not for a minority of people that will not be in any groups.
        if (rand(1, 50) != 1) {

            //Generate the number of groups the user has joined.
            $nbOfGroups = rand(2, 10);
            $listOfGroupsJoinedByTheUser = [];

            //For the number of groups decided, generate the other data.
            for ($j = 1; $j <= $nbOfGroups; $j++) {
                $id++;
                $join['$id'] = $id;

                //Generate the groups joined randomly:
                $lastLeftDate = null;
                $groupid = rand(0, 11);
                if (in_array($groupid, $listOfGroupsJoinedByTheUser) == true) {
                    foreach ($joins as $onejoin) {
                        //Take the last join with user_id and groupid that are managed:
                        if ($onejoin['user_id'] == $i && $onejoin['group_id'] == $groupid) {
                            if ($onejoin['end'] != null) {
                                //If the user has joined and left the group, he has the right to join again.
                                $lastLeftDate = $onejoin['end'];
                            } else {
                                //If the user has joined and not left the group, he hasn't not the right to join again before he left the group. So the groupid need to be changed:

                                //Generate a new group id that is not in the table.
                                while (in_array($groupid, $listOfGroupsJoinedByTheUser) == true) {
                                    $groupid = rand(1, 11);
                                }
                            }
                        }
                    }
                }

                //Save groupid value
                $join['group_id'] = $groupid;

                //Add to the list of the groups joined by the user
                $listOfGroupsJoinedByTheUser[] = $groupid;

                //If the lastLeftDate is not null, the user has already joined and left, so the start must be after the end of the last join.
                if ($lastLeftDate != null) {
                    $join['start'] = getRandomDateFormated(strtotime($lastLeftDate));
                } else {
                    $join['start'] = getRandomDateFormated();
                }

                echo " \n";
                if (rand(0, 10) == 0) {
                    $join['end'] = getRandomDateFormated(strtotime($join['start']));
                } else {
                    $join['end'] = null;
                }

                //In most of cases the user is accepted.
                if (rand(0, 20) == 0) {
                    $join['accepted'] = 0;
                } else {
                    $join['accepted'] = 1;
                }
                echo $join['group_id'] . " \n";
                echo $join['start'] . " \n";
                echo $join['end'] . " \n";
                $joins[] = $join;
            }
        }
        die();
    }
}

//Source: https://stackoverflow.com/questions/4356289/php-random-string-generator#answer-4356295
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//dataUsers();
//dataGroups();
data_user_join_group();
?>