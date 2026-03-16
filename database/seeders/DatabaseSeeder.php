<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Actor;
use App\Models\Role;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];
        Role::insert($roles);

        $categories = [
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Comedy'],
            ['name' => 'Drama'],
            ['name' => 'Fantasy'],
            ['name' => 'Horror'],
            ['name' => 'Mystery'],
            ['name' => 'Romance'],
            ['name' => 'Thriller'],
            ['name' => 'Sci-Fi (Science Fiction)'],
            ['name' => 'Fantasy'],
            ['name' => 'Mystery'],
            ['name' => 'Crime'],
            ['name' => 'Animation'],
            ['name' => 'Family'],
            ['name' => 'Historical'],
            ['name' => 'Biography']
        ];
        Category::insert($categories);

        $actors = [
            [
                'name' => 'Robert De Niro',
                'profile_picture' => 'actor_pictures/deniro.jpg',
            ],
            [
                'name' => 'Al Pacino',
                'profile_picture' => 'actor_pictures/pacino.jpg',
            ],
        ];
        Actor::insert($actors);

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'password' => Hash::make('password'),
                'role_id' => 1
            ]
        ];
        User::insert($users);

        $movies = [
            [
                'title' => 'Мобилна връзка',
                'description' => 'Какво ще направите, ако нечий живот виси на косъм и възможността за спасението му е изцяло във вашите ръце?За Райън (Крис Евънс) този момент идва с с тресаково телефонно обаждане за помощ от Джесика Мартин (носителката на Оскар Ким Бейсинджър). Отвлечена и затворена на неизвестно място, Джесика умолява Райън да й помогне, преди похитителите й да се върнат да я убият.Без начин да разбере къде е тя и с бързо изчерпваща се батерия на мобилния си телефон, Райън се впуска в трескаво, безкрайно преследване с престъпниците в този повдигащ адреналина екшън трилър.',
                'image' => 'movies_images/mobile-connection.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/BmV7Vq_WYLY?si=NdyGjmpjWm60DS7I',
                'rating' => 10,
                'year' => 2005,
                'duration' => '1:33'
            ],
            [
                'title' => 'Едва сега започваме',
                'description' => 'Някога блестящ адвокат, защитавал интересите на главатарите на най-големите престъпни групировки, Дюк и екс-агент от ФБР смятат да се оттеглят и да отидат да живеят в приятна вила на остров Капри, по програмата за защита на свидетели. От ден на ден те скучаят все повече и повече, единственото им забавление е да преследват топката за голф на игрището. Но скоро им се налата да загърбят своите малки дрязги, статус и възраст, за да дадат един последен отпор на мафията...',
                'image' => 'movies_images/almost-got-me.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/JizsTwuHGJk?si=yheANmFueoQSIftn',
                'rating' => 7,
                'year' => 2017,
                'duration' => '1:30'
            ],
            [
                'title' => 'Изгнаникът',
                'description' => 'Бившият агент на ЦРУ заедно със своенравната си дъщеря заминава за Белгия, с надеждата за нов старт. Мъжът започва работа в международна корпорация и на пръв поглед всичко протича по план. Но всичко се променя, когато един ден той пристига на работа и открива, че цялата сграда е пуста, а асистента му го напада с цел да го убие.',
                'image' => 'movies_images/erased.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/vGBYqSZfDiY?si=R4ZrtNaSP4AXnpMN',
                'rating' => 10,
                'year' => 2012,
                'duration' => '1:29'
            ],
            [
                'title' => 'Чуждоземец',
                'description' => 'По време на царуването на викингите, Кайнан - човек от далечен свят идва на Земята. Той води със себе си извънземен хищник, на име Морвен. Въпреки че и мъжът, и чудовището търсят отмъщение, заради насилието, упражнено върху тях, Кайнан се съюзява с викингите срещу Морвен благодарение от оръжия, познати от Желязната ера.',
                'image' => 'movies_images/outlander.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/_CVFO_q67gA?si=Zp3rWYVkNWRw-S0e',
                'rating' => 10,
                'year' => 2008,
                'duration' => '1:54'
            ],
            [
                'title' => 'Момичето Самурай',
                'description' => 'Филмът разказва историята на 19-годишно японско момиче на име Хевън, която научава, че богатият бизнесмен, който я е осиновил като бебе, всъщност е шеф на Якуза, японската мафия и брутално е убил любимия ѝ брат. Тя се откъсва от семейството и започва обучение, за да стане самурай. С помощта на група американски приятели се заема да срине зловещата империя на баща си...',
                'image' => 'movies_images/samurai-woman.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/FtqrYvq8lc4?si=K60oI3kua5_D28kO',
                'rating' => 9,
                'year' => 2008,
                'duration' => '4:08'
            ],
            [
                'title' => 'Телепорт',
                'description' => ' "Телепорт" разказва за младия Дейвид (Хейдън Кристенсен), който изведнъж открива, че има способността да се телепортира. Той иска да отмъсти за смъртта на майка си, но същевременно трябва да се измъкне от лапите на специалните служби и да уреди отношенията си с останалите телепортери, защото той не е единственият... От хиляда години Тайно общество ги преследва, за да ги унищожи.  Скоро Дейвид ще научи за истинската стойност на новите си способности...',
                'image' => 'movies_images/teleporter.jpg',
                'iframe_url' => 'https://www.youtube.com/embed/AsbbZPGzX_s?si=HuGv8fJ_4XIHDZY6',
                'rating' => 10,
                'year' => 2008,
                'duration' => '4:08'
            ]
        ];

        Movie::insert($movies);
    }
}
