<?php
namespace TestData\Service;
use \Photo\Model\Album as AlbumModel;
use \Photo\Model\Album as PhotoModel;

class Photo extends AbstractTestDataService
{
    public function generateTestData()
    {
        for($i = 0; $i < 2; $i++)
        {
            $this->generateAlbum();
        }
    }

    public function generateAlbum($reclevel = 0, $parent = null)
    {
        if ($reclevel < 3) {
            $endDateTime = $this->faker->dateTime->format("Y-m-d H:i:s");
            $startDateTime = $this->faker->dateTime($endDateTime)->format("Y-m-d H:i:s");
            $albumCount = (rand(0, 1) == 1) ? rand(1, 3) : 0;
            $photoCount = $this->faker->numberBetween(0, 100);
            $name = join($this->faker->words(2), ' ');
            $album = new AlbumModel();
            $album->setName($name);
            $album->setStartDateTime(new \DateTime($startDateTime));
            $album->setEndDateTime(new \DateTime($endDateTime));
            if (!is_null($parent)) {
                $album->setParent($parent);

            }
            $this->em->persist($album);
            for ($i = 0; $i < $photoCount; $i++) {
                $this->generatePhoto($album);
            }
            for ($i = 0; $i < $albumCount; $i++) {
                $this->generateAlbum($reclevel + 1, $album);
            }

            $this->em->flush();

        }
    }

    public function generatePhoto($album)
    {
        $path = $this->faker->image('/tmp', 640, 480);
        $photo = $this->getAdminService()->storeUploadedPhoto($path, $album);
        $photo->setDateTime(new \DateTime($this->faker->dateTime->format("Y-m-d H:i:s")));
        $photo->setArtist((htmlentities($this->faker->name)));
        $photo->setCamera('Nikon D40');
        $photo->setFlash($this->faker->randomElement(array(0, 1)));
        $photo->setFocalLength($this->faker->numberBetween(10, 200));
        $photo->setExposureTime($this->faker->randomFloat(3, 0, 10));
        $photo->setShutterSpeed($this->faker->numerify('1/#00'));
        $photo->setAperture('f' . $this->faker->randomFloat(1, 2, 10));
        $photo->setIso($this->faker->numerify('#000'));
        $this->em->flush();
    }

    /**
     * Gets the photo admin service.
     *
     * @return \Photo\Service\Admin
     */
    public function getAdminService()
    {
        return $this->sm->get('photo_service_admin');
    }
}
