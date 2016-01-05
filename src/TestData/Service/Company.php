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

    }

    public function generateCompany()
    {
        $company = new CompanyModel();
        $companyDutch = new CompanyI18nModel();
        $companyEnglish = new CompanyI18nModel();

        $name = $this->faker->word;
        $company->setName($name);
        $company->setSlugName($name);
        $company->setAddress($this->faker->address);
        $company->setEmail($this->faker->companyEmail);
        $company->setPhone($this->faker->phoneNumber);
        $path = $this->faker->image('/tmp', 200, 40);
        $logo = $this->getFileStorageService()->storeUploadedFile($path);
        $companyDutch->setWebsite($this->faker->url);
        $companyDutch->setSlogan($this->faker->sentence);
        $companyDutch->setLogo($logo);
        $companyDutch->setDescription($this->faker->text());
        $companyDutch->setLanguage('nl');

        $companyEnglish->setWebsite($this->faker->url);
        $companyEnglish->setSlogan($this->faker->sentence);
        $companyEnglish->setLogo($logo);
        $companyEnglish->setDescription($this->faker->text());
        $companyEnglish->setLanguage('nl');
        $this->em->persist($company);
        $company->addTranslation($companyDutch);
        $company->addTranslation($companyEnglish);
        $this->em->persist($company);
        $package = new JobPackageModel();
        $package->setCompan($company);
        $startDate = $this->faker->dateTimeBetween('-1 years','now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 years');
        $package->setStartingDate(new \DateTime($startDate));
        $package->setExpirationDate(new \DateTime($endDate));
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

        $this->em->persist($job);
        $package->addJob($job);
    }
}