<?php

namespace App\DataFixtures;

use App\Entity\TodoItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TodoItemsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $todoList = ['First todo', 'SecondTodo', '3-rd TODO'];

        for ($i = 0; $i < count($todoList); ++$i) {
            $todoItem = new TodoItem();
            $date = new \DateTime(date('Y-m-d H:i:s', strtotime(rand(1609459200, 1632614400))));
            $todoItem
                ->setAuthor('ykropchik')
                ->setName($i)
                ->setDescription($todoList[$i])
                ->setDateCreate(new \DateTime('now'))
                ->setIsDone(false);

            $manager->persist($todoItem);
            $manager->flush();
        }
    }
}
