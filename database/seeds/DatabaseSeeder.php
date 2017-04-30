<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('user')->insert([
            'name' => 'Fletch',
            'email' => 'fletch@fletchy.net',
            'password' => bcrypt('vs123dy4db87vzfx5dvr6d54b'),
        ]);

        DB::table('category')->insert(['name' => 'Funny']);
        DB::table('category')->insert(['name' => 'Animals']);
        DB::table('category')->insert(['name' => 'News']);
        DB::table('category')->insert(['name' => 'Food']);
        DB::table('category')->insert(['name' => 'Creepy']);
        DB::table('category')->insert(['name' => 'Feels']);
        DB::table('category')->insert(['name' => 'Gaming']);
        DB::table('category')->insert(['name' => 'Nostalgia']);

        DB::table('post')->insert([
            'user_id' => 1,
            'category_id' => 1,
            'title' => '13 People Whose Snapchat Game Is Way Stronger Than Yours',
            'slug' => '13-people-whose-snapchat-game-is-way-stronger-than-yours',
            'description' => 'When your Snap is so strong, you\'ve got to share.',
            'content' => '<p>Snapchat can be used for all kinds of things, if you&rsquo;re a regular user you&rsquo;ll notice many different kinds of snaps, couples who recently broke up and want to make each other jealous, people who pretend like their life is perfect, and of course people who just snap anything especially if they have a pet then 99% of their snaps will be about that pet.&nbsp;But we all have that one friend who never fails to entertain us through his snaps, the following pictures demonstrate&nbsp;how the perfect Snap should be like.</p>\r\n<div>\r\n<h1>1. When your artistic sense kicks in.</h1>\r\n<p><img src="http://localhost/postize/public/content/2016/03/LyPDNq_3.jpg" alt="" width="460" height="451" /></p>\r\n</div>\r\n<p><span class="source"> source: <a>Hellou.co.uk</a> </span></p>\r\n<h1>2. Has science gone too far ?</h1>\r\n<p><img src="http://localhost/postize/public/content/2016/03/ZlJfux_3.jpg" alt="" width="499" height="666" /></p>\r\n<div><a href="http://acidface.tumblr.com/" target="_blank">Tumblr &ndash; Acidface</a>\r\n<h1>3.&nbsp;The Tonight Show Starring&nbsp;Joppa Fallston.</h1>\r\n<p><img src="http://localhost/postize/public/content/2016/03/JpXvBc_3.jpg" alt="" width="500" height="884" /></p>\r\n</div>\r\n<div><a href="http://fallontonight.tumblr.com/post/80370725714/thejimmyfallontonightshow-builtfromfire-just" target="_blank">Tumblr &ndash; fallontonight</a></div>\r\n<div>&nbsp;</div>\r\n<div>\r\n<h1>4. Waking up to this must be terrifying.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/VmQylE_3.jpg" alt="" width="499" height="651" /></p>\r\n</div>\r\n<div><a href="http://thisisnothinggoaway.tumblr.com/post/78670130503" target="_blank">Tumblr &ndash; thisisnothinggoaway</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>5. When you have too much time on your hands.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/brtGLN_3.jpg" alt="" width="500" height="744" /></p>\r\n</div>\r\n<div><a href="http://izismile.com/2014/03/07/morning_picdump_56_pics.html" target="_blank">Izismile</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<h1>6. Whenever some stranger tries to&nbsp;engage me in conversation.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/lMvaAE_3.jpg" alt="" width="500" height="874" /></p>\r\n</div>\r\n<div><a href="http://justrandomdesigns.tumblr.com/" target="_blank">Just Random Designs</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>7. Not following the herd.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/nXvWTD_3.jpg" alt="" width="500" height="820" /></p>\r\n</div>\r\n<div><a href="http://www.polyvore.com/24_funny_clever_snapchat_pics/thing?id=99588071" target="_blank">Polyvore</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>8. When snow snitches on you.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/JOYbLq_3.jpg" alt="" width="500" height="745" /></p>\r\n</div>\r\n<div><a href="https://www.reddit.com/r/funny/comments/23akb1/winter_is_not_a_wingman/" target="_blank">Reddit &ndash; SIM_GR8_1</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>9. Some pets are just too spoiled.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/ORJDHy_3.jpg" alt="" width="499" height="645" /></p>\r\n</div>\r\n<div><a href="http://theberry.com/2014/04/17/these-people-are-better-at-snapchat-than-you-32-photos/" target="_blank">The Berry</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>10.&nbsp;When you treat your pets like children.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/lNcKoV_3.jpg" alt="" width="499" height="575" /></p>\r\n</div>\r\n<div><a href="http://crazyhyena.com/dog-ready-cold-weather-overdressed-funny" target="_blank">Crazy Hyena</a></div>\r\n<div>\r\n<div>\r\n<div>\r\n<div>\r\n<h1>11. Really Human ?.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/BJipqH_3.jpg" alt="" width="500" height="634" /></p>\r\n</div>\r\n<div><a href="http://i.imgur.com/qvVjV5k.jpg" target="_blank">Imgur</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>12. When you have finals next week and your friend sends you this Snap.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/GEZQdL_3.jpg" alt="" width="500" height="888" /></p>\r\n</div>\r\n<div><a href="http://www.appszoom.com/android_applications/entertainment/best-snapchats_jesdr.html" target="_blank">Apps Zoom</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n<div>\r\n<h1>13. The Booty goes up and down.</h1>\r\n<div>\r\n<div>\r\n<div>\r\n<div>&nbsp;</div>\r\n<p><img src="http://localhost/postize/public/content/2016/03/mqfOQj_3.jpg" alt="" width="500" height="750" /></p>\r\n</div>\r\n<div><a href="http://theberry.com/2014/04/17/these-people-are-better-at-snapchat-than-you-32-photos/" target="_blank">The Berry</a></div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<h2><a href="http://diply.com/virginradiolebanon/20-people-who-win-snapchat/223224">h/t Diply</a></h2>\r\n</div>\r\n</div>\r\n</div>\r\n</div>',
            'image' => 'http://localhost/postize/public/thumbs/lLIniR_1.jpg',
            'status' => 1
        ]);
    }
}
