<?php
namespace TestData\Service;

use \Company\Model\Company as CompanyModel;
use \Company\Model\CompanyI18n as CompanyI18nModel;
use \Company\Model\CompanyJobPackage as JobPackageModel;
use \Company\Model\Job as JobModel;

class Company extends AbstractTestDataService
{
    public function generateTestData()
    {
        for($i = 0; $i < 20; $i++)
        {
            $this->generateCompany();
        }
    }

    public function generateCompany()
    {
        $company = new CompanyModel();
        $companyDutch = new CompanyI18nModel('nl', $company);
        $companyEnglish = new CompanyI18nModel('en', $company);

        $name = $this->faker->word;
        $company->setName($name);
        $company->setSlugName($name);
        $company->setAddress($this->faker->address);
        $company->setEmail($this->faker->companyEmail);
        $company->setPhone($this->faker->phoneNumber);
        $company->setHidden(false);
        $path = $this->faker->image('/tmp', 200, 40);
        $logo = $this->getFileStorageService()->storeFile($path);
        $companyDutch->setWebsite($this->faker->url);
        $companyDutch->setSlogan($this->faker->sentence);
        $companyDutch->setLogo($logo);
        $companyDutch->setDescription($this->faker->text());

        $companyEnglish->setWebsite($this->faker->url);
        $companyEnglish->setSlogan($this->faker->sentence);
        $companyEnglish->setLogo($logo);
        $companyEnglish->setDescription($this->faker->text());

        $this->em->persist($company);
        $package = new JobPackageModel();
        $package->setCompany($company);
        $startDate = $this->faker->dateTimeBetween('-1 years','now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 years');
        $package->setStartingDate($startDate);
        $package->setExpirationDate($endDate);
        $package->setPublished(true);
        $this->em->persist($package);
        for ($i = 0; $i < $this->faker->randomDigit; $i++) {
            $this->generateJob($package);
        }
        $this->em->flush();
    }

    public function generateJob($package)
    {
        $job = new JobModel();
        $name = join($this->faker->words(3), ' ');
        $job->setName($name);
        $job->setSlugName($name);
        $job->setActive($this->faker->randomElement(array(0, 1)));
        $job->setLanguage($this->faker->randomElement(array('nl', 'en')));
        $job->setWebsite($this->faker->url);
        $job->setPhone($this->faker->phoneNumber);
        $job->setEmail($this->faker->companyEmail);
        $job->setDescription($this->faker->text());

        $job->setPackage($package);
        $this->em->persist($job);
    }

    /**
     * Gets the storage service.
     *
     * @return \Application\Service\Storage
     */
    public function getFileStorageService()
    {
        return $this->sm->get('application_service_storage');
    }
}