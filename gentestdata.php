#!/usr/bin/env php
<?php
require_once 'Faker/src/autoload.php';
$faker = Faker\Factory::create('nl_NL');

switch(strtolower($argv[1])) {
    case 'company':
        for($i=0;$i<30;$i++) {
            gen_company();
        }
        break;
    case 'education':
        for($i=0;$i<30;$i++) {
            gen_course();
        }
        break;
    case 'photo':
        for($i=0;$i<4;$i++) {
            gen_album();
        }
        break;
    case 'user':
        for($i=0;$i<100;$i++) {
            gen_user();
        }
        break;
    default:
        print 'USAGE '. basename(__FILE__) . ' <module_name>' . PHP_EOL;
        print 'Valid modules: Company, Education, Photo, User';
        break;
}

function gen_company()
{
    global $faker;
    $name = $faker->word;
    $asciiName = $name;
    $address = mysql_escape_string($faker->address);
    $website = $faker->url;
    $slogan = $faker->sentence;
    $email = $faker->companyEmail;
    $phone = $faker->phoneNumber;
    $logo = 'null';
    $description = $faker->text();
    print "INSERT INTO `Company`(`name`, `asciiName`, `address`, `website`, `slogan`, `email`, `phone`, `logo`, `description`) VALUES ('$name', '$asciiName', '$address', '$website', '$slogan', '$email', '$phone', '$logo', '$description');" . PHP_EOL;
    for ($i = 0; $i < $faker->randomDigit; $i++) {
        gen_company_job();
    }
}

function gen_company_job()
{
    global $faker;
    $name = join($faker->words(3), ' ');
    $ascii_name = $name;
    $active = $faker->randomElement(array(0, 1));
    $website = $faker->url;
    $phone = $faker->phoneNumber;
    $email = $faker->companyEmail;
    $description = $faker->text();
    print "INSERT INTO `Job`(`company_id`, `name`, `ascii_name`, `active`, `website`, `phone`, `email`, `description`) VALUES ((SELECT MAX( id ) FROM Company), '$name', '$ascii_name', '$active', '$website', '$phone', '$email', '$description');" . PHP_EOL;
}

function gen_user()
{
    global $faker;
    $lidnr = $faker->unique()->randomNumber(4);
    $email = $faker->unique()->email;
    $password = '$2y$13$5WprUFHONf2tcFOKU2rlM.nhTs2x1m4rHEezFcZrMLm6qq.4hm6kC'; //==password

    $lastName = mysql_escape_string($faker->lastName);
    $firstName = mysql_escape_string(explode(' ', $faker->name)[0]);
    $initials = $firstName[0] . '.';
    print "INSERT INTO `Member`(`lidnr`, `email`, `lastName`, `middleName`, `initials`, `firstName`) VALUES ('$lidnr', '$email', '$lastName', '', '$initials', '$firstName');" . PHP_EOL;
    print "INSERT INTO `User` (`lidnr`, `email`, `password`) VALUES('$lidnr', '$email', '$password');" . PHP_EOL;
    print "INSERT INTO `UserRole`(`lidnr_id`, `role`) VALUES ($lidnr, 0);" . PHP_EOL;
}

function gen_course()
{
    global $faker;
    $code = strtoupper($faker->unique()->bothify('#??#0'));
    $name = join($faker->words(3), ' ');
    $url = 'http://venus.tue.nl/';
    $year = $faker->year;
    $quartile = $faker->numerify('Q#');
    print "INSERT INTO `Course`(`code`, `parent_code`, `name`, `url`, `year`, `quartile`) VALUES ('$code', NULL, '$name', '$url', '$year', '$quartile');" . PHP_EOL;
    for ($i = 0; $i < $faker->randomDigit; $i++) {
        gen_exam($code);
    }

}

function gen_exam($course)
{
    global $faker;
    $date = $faker->date();
    $author = mysql_escape_string($faker->name);
    $type = $faker->randomElement(array('exam', 'summary'));
    print "INSERT INTO `Exam` (`course_code`, `date`, `type`, `author`) VALUES ('$course', '$date', '$type', '$author');" . PHP_EOL;

}

function gen_album($reclevel = 0, $album_offset = 0)
{
    if ($reclevel < 3) {
        global $faker;
        $parent_id = $reclevel == 0 ? 'NULL' : '(SELECT MAX( album_id ) FROM Photo) - ' . $album_offset;
        $endDateTime = $faker->dateTime->format("Y-m-d H:i:s");
        $startDateTime = $faker->dateTime($endDateTime)->format("Y-m-d H:i:s");
        $albumCount = (rand(0, 1) == 1) ? $faker->randomDigit : 0;
        $photoCount = $faker->numberBetween(0, 100);
        $name = join($faker->words(2), ' ');
        print "INSERT INTO `Album`(`parent_id`, `startDateTime`, `endDateTime`, `name`, `photoCount`, `albumCount`, `coverPath`) VALUES ($parent_id, '$startDateTime', '$endDateTime', '$name', '$photoCount', '$albumCount', 'null');" . PHP_EOL;
        for ($i = 0; $i < $photoCount; $i++) {
            gen_photo();
        }
        for ($i = 0; $i < $albumCount; $i++) {
            gen_album($reclevel + 1, $i);
        }

    }
}

function gen_photo()
{
    global $faker;
    $path = str_replace('./photo/', '', $faker->image('./photo', 640, 480));
    $dateTime = $faker->dateTime->format("Y-m-d H:i:s");
    $artist = mysql_escape_string(htmlentities($faker->name));
    $camera = 'Nikon D40';
    $flash = $faker->randomElement(array(0, 1));
    $focalLength = $faker->numberBetween(10, 200);
    $exposureTime = $faker->randomFloat(3, 0, 10);
    $shutterSpeed = $faker->numerify('1/#00');
    $aperture = 'f' . $faker->randomFloat(1, 2, 10);
    $iso = $faker->numerify('#000');
    print "INSERT INTO `Photo`(`album_id`, `dateTime`, `artist`, `camera`, `flash`, `focalLength`, `exposureTime`, `shutterSpeed`, `aperture`, `iso`, `path`, `smallThumbPath`, `largeThumbPath`) VALUES ((SELECT MAX( id ) FROM Album), '$dateTime', '$artist', '$camera', '$flash', '$focalLength', '$exposureTime', '$shutterSpeed', '$aperture', '$iso', '$path', '$path', '$path');" . PHP_EOL;
}

