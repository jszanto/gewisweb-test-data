<?php
namespace TestData\Service;

use \Frontpage\Model\Poll as PollModel;
use \Frontpage\Model\PollOption as PollOptionModel;
use \Frontpage\Model\PollVote as PollVoteModel;
use \Frontpage\Model\PollComment as PollCommentModel;

class Poll extends AbstractTestDataService
{
    public function generateTestData()
    {
        for($i = 0; $i < 50; $i++) {
            $this->generatePoll();
        }
    }

    public function generatePoll()
    {
        $userService = $this->sm->get('testdata_service_user');
        $poll = new PollModel();
        $poll->setDutchQuestion($this->faker->sentence(10));
        $poll->setEnglishQuestion($this->faker->sentence(10));
        $poll->setExpiryDate(new \DateTime());
        $poll->setCreator($userService->getRandomUser());
        $poll->setApprover($userService->getRandomUser());
        $poll = $this->addComments($poll);
        $poll = $this->addOptions($poll);
        $poll = $this->addVotes($poll);
        $this->em->persist($poll);
        $this->em->flush();
    }

    public function addComments($poll)
    {
        $userService = $this->sm->get('testdata_service_user');
        for ($i = 0; $i < $this->faker->numberBetween(0, 10); $i++) {
            $comment = new PollCommentModel();
            $comment->setAuthor($this->faker->words());
            //TODO
            //$comment->setTitle($this->faker->words());
            //$comment->setDate()
            $comment->setContent($this->faker->paragraph());
            $comment->setUser($userService->getRandomUser());

            $poll->addComment($comment);
        }

        return $poll;
    }

    public function addOptions($poll)
    {
        for ($i = 0; $i < $this->faker->numberBetween(2, 15); $i++) {
            $option = new OptionModel();
            $option->setDutchText($this->faker->sentence(6));
            $option->setEnglishText($this->faker->sentence(6));
            $poll->addOptions([$option]);
        }

        return $poll;
    }

    public function addVotes($poll)
    {
        $users = $this->sm->get('testdata_service_user')->getRandomUsers($this->faker->numberBetween(0, 40));
        $options = $poll->getOptions();
        foreach ($users as $user) {
            $option = $this->faker->randomElement($options);
            $pollVote = new PollVoteModel();
            $pollVote->setRespondent($user);
            $pollVote->setPoll($poll);
            $option->addVote($pollVote);
            $this->em->persist($option);
        }

        return $poll;
    }
}