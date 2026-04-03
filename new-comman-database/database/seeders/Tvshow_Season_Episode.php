<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Tvshow_Season_Episode extends Seeder
{
    public function run(): void
    {
        $episodes = [
            // Money Heist (TV ID: 5) | Seasons: 1 (ID: 3), 2 (ID: 13), 3 (ID: 23)
            ["tv_show_id" => 5, "season_id" => 3, "episode_name" => "Efectuar lo acordado", "episode_number" => 1, "release_date" => "2017-05-02", "timeParts" => ["hour" => "01", "minute" => "30", "second" => "00"], "description" => "The Professor recruits a team to carry out the biggest heist in Spanish history.", "directors" => "Álex Pina", "presenter" => "Úrsula Corberó, Álvaro Morte, Itziar Ituño"],
            ["tv_show_id" => 5, "season_id" => 3, "episode_name" => "Imprudencias letales", "episode_number" => 2, "release_date" => "2017-05-09", "timeParts" => ["hour" => "01", "minute" => "28", "second" => "00"], "description" => "Tensions rise inside the Royal Mint as the plan begins to crack.", "directors" => "Álex Pina", "presenter" => "Úrsula Corberó, Álvaro Morte"],
            ["tv_show_id" => 5, "season_id" => 13, "episode_name" => "We’re Back", "episode_number" => 1, "release_date" => "2019-07-19", "timeParts" => ["hour" => "01", "minute" => "35", "second" => "00"], "description" => "The Professor returns with a new and riskier plan.", "directors" => "Álex Pina", "presenter" => "Álvaro Morte, Úrsula Corberó"],
            ["tv_show_id" => 5, "season_id" => 13, "episode_name" => "Aikido", "episode_number" => 2, "release_date" => "2019-07-19", "timeParts" => ["hour" => "01", "minute" => "32", "second" => "00"], "description" => "The heist spirals into chaos as outside pressure mounts.", "directors" => "Álex Pina", "presenter" => "Álvaro Morte, Úrsula Corberó"],
            ["tv_show_id" => 5, "season_id" => 23, "episode_name" => "Game Over", "episode_number" => 1, "release_date" => "2020-04-03", "timeParts" => ["hour" => "01", "minute" => "40", "second" => "00"], "description" => "The final phase of the plan begins with unexpected twists.", "directors" => "Álex Pina", "presenter" => "Álvaro Morte, Úrsula Corberó"],
            ["tv_show_id" => 5, "season_id" => 23, "episode_name" => "The End of the Road", "episode_number" => 2, "release_date" => "2020-04-03", "timeParts" => ["hour" => "01", "minute" => "45", "second" => "00"], "description" => "The Professor faces the consequences of every decision.", "directors" => "Álex Pina", "presenter" => "Álvaro Morte, Úrsula Corberó"],

            // Breaking Bad (TV ID: 6) | Seasons: 1 (ID: 4), 2 (ID: 14), 3 (ID: 24)
            ["tv_show_id" => 6, "season_id" => 4, "episode_name" => "Pilot", "episode_number" => 1, "release_date" => "2008-01-20", "timeParts" => ["hour" => "01", "minute" => "25", "second" => "00"], "description" => "A chemistry teacher receives a terminal diagnosis and turns to crime.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],
            ["tv_show_id" => 6, "season_id" => 4, "episode_name" => "Cat’s in the Bag", "episode_number" => 2, "release_date" => "2008-01-27", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "Walt and Jesse attempt to clean up the aftermath of their first crime.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],
            ["tv_show_id" => 6, "season_id" => 14, "episode_name" => "Seven Thirty-Seven", "episode_number" => 1, "release_date" => "2009-03-08", "timeParts" => ["hour" => "01", "minute" => "22", "second" => "00"], "description" => "Walt faces mounting pressure from dangerous enemies.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],
            ["tv_show_id" => 6, "season_id" => 14, "episode_name" => "Grilled", "episode_number" => 2, "release_date" => "2009-03-15", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "A confrontation threatens to end everything.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],
            ["tv_show_id" => 6, "season_id" => 24, "episode_name" => "No Más", "episode_number" => 1, "release_date" => "2010-03-21", "timeParts" => ["hour" => "01", "minute" => "25", "second" => "00"], "description" => "Walt tries to quit the drug business, but danger follows.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],
            ["tv_show_id" => 6, "season_id" => 24, "episode_name" => "Caballo Sin Nombre", "episode_number" => 2, "release_date" => "2010-03-28", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "Family and crime begin to collide.", "directors" => "Vince Gilligan", "presenter" => "Bryan Cranston, Aaron Paul"],

            // Game of Thrones (TV ID: 7) | Season: 1 (ID: 5), 2(15), 3(25)
            ["tv_show_id" => 7, "season_id" => 5, "episode_name" => "Winter Is Coming", "episode_number" => 1, "release_date" => "2011-04-17", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "Power struggles begin among noble families in Westeros.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Sean Bean, Emilia Clarke"],
            ["tv_show_id" => 7, "season_id" => 5, "episode_name" => "The Kingsroad", "episode_number" => 2, "release_date" => "2011-04-24", "timeParts" => ["hour" => "01", "minute" => "05", "second" => "00"], "description" => "The Stark family is torn apart by royal duty.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Sean Bean, Emilia Clarke"],
            ["tv_show_id" => 7, "season_id" => 15, "episode_name" => "The North Remembers", "episode_number" => 1, "release_date" => "2012-04-01", "timeParts" => ["hour" => "01", "minute" => "12", "second" => "00"], "description" => "The Seven Kingdoms brace for war as new alliances form.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Peter Dinklage, Emilia Clarke"],
            ["tv_show_id" => 7, "season_id" => 15, "episode_name" => "The Night Lands", "episode_number" => 2, "release_date" => "2012-04-08", "timeParts" => ["hour" => "01", "minute" => "08", "second" => "00"], "description" => "Arya makes new friends while Tyrion confronts danger.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Peter Dinklage, Maisie Williams"],
            ["tv_show_id" => 7, "season_id" => 25, "episode_name" => "Valar Dohaeris", "episode_number" => 1, "release_date" => "2013-03-31", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Jon Snow faces a test of loyalty beyond the Wall.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Kit Harington, Emilia Clarke"],
            ["tv_show_id" => 7, "season_id" => 25, "episode_name" => "Dark Wings, Dark Words", "episode_number" => 2, "release_date" => "2013-04-07", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "Secrets spread across Westeros as danger grows.", "directors" => "David Benioff, D.B. Weiss", "presenter" => "Kit Harington, Peter Dinklage"],

            // Stranger Things: TV ID 8 | Seasons: 1(6), 2(16), 3(26)
            ["tv_show_id" => 8, "season_id" => 6, "episode_name" => "The Vanishing of Will Byers", "episode_number" => 1, "release_date" => "2016-07-15", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "A boy disappears, uncovering dark secrets.", "directors" => "The Duffer Brothers", "presenter" => "Millie Bobby Brown, Finn Wolfhard"],
            ["tv_show_id" => 8, "season_id" => 6, "episode_name" => "The Weirdo on Maple Street", "episode_number" => 2, "release_date" => "2016-07-15", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "The group meets a mysterious girl with strange powers.", "directors" => "The Duffer Brothers", "presenter" => "Millie Bobby Brown, Finn Wolfhard"],
            ["tv_show_id" => 8, "season_id" => 16, "episode_name" => "MADMAX", "episode_number" => 1, "release_date" => "2017-10-27", "timeParts" => ["hour" => "01", "minute" => "12", "second" => "00"], "description" => "A new girl shakes up the group as darkness returns.", "directors" => "The Duffer Brothers", "presenter" => "Millie Bobby Brown, Sadie Sink"],
            ["tv_show_id" => 8, "season_id" => 16, "episode_name" => "Trick or Treat, Freak", "episode_number" => 2, "release_date" => "2017-10-27", "timeParts" => ["hour" => "01", "minute" => "08", "second" => "00"], "description" => "Strange visions haunt Will while danger lurks nearby.", "directors" => "The Duffer Brothers", "presenter" => "Noah Schnapp, Winona Ryder"],
            ["tv_show_id" => 8, "season_id" => 26, "episode_name" => "Suzie, Do You Copy?", "episode_number" => 1, "release_date" => "2019-07-04", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "The kids investigate a new threat in Hawkins.", "directors" => "The Duffer Brothers", "presenter" => "Millie Bobby Brown, Finn Wolfhard"],
            ["tv_show_id" => 8, "season_id" => 26, "episode_name" => "The Mall Rats", "episode_number" => 2, "release_date" => "2019-07-04", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Secrets unfold inside the new shopping mall.", "directors" => "The Duffer Brothers", "presenter" => "Gaten Matarazzo, Caleb McLaughlin"],

            // The Walking Dead (TV ID: 9) | Seasons: 1 (ID: 7), 2 (ID: 17), 3 (ID: 27)
            ["tv_show_id" => 9, "season_id" => 7, "episode_name" => "Days Gone Bye", "episode_number" => 1, "release_date" => "2010-10-31", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "A sheriff wakes up to a zombie apocalypse.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Norman Reedus"],
            ["tv_show_id" => 9, "season_id" => 7, "episode_name" => "Guts", "episode_number" => 2, "release_date" => "2010-11-07", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Rick attempts a dangerous escape in the city.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Steven Yeun"],
            ["tv_show_id" => 9, "season_id" => 17, "episode_name" => "What Lies Ahead", "episode_number" => 1, "release_date" => "2011-10-16", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "The survivors search for a missing child.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Sarah Wayne Callies"],
            ["tv_show_id" => 9, "season_id" => 17, "episode_name" => "Bloodletting", "episode_number" => 2, "release_date" => "2011-10-23", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "Secrets threaten to tear the group apart.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Jon Bernthal"],
            ["tv_show_id" => 9, "season_id" => 27, "episode_name" => "Seed", "episode_number" => 1, "release_date" => "2012-10-14", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "The group searches for a safe haven.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Norman Reedus"],
            ["tv_show_id" => 9, "season_id" => 27, "episode_name" => "Sick", "episode_number" => 2, "release_date" => "2012-10-21", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "A deadly illness spreads within the group.", "directors" => "Frank Darabont", "presenter" => "Andrew Lincoln, Norman Reedus"],

            // Peaky Blinders (TV ID: 10) | Seasons: 1 (ID: 8), 2 (ID: 18), 3 (ID: 28)
            ["tv_show_id" => 10, "season_id" => 8, "episode_name" => "Episode 1", "episode_number" => 1, "release_date" => "2013-09-12", "timeParts" => ["hour" => "01", "minute" => "05", "second" => "00"], "description" => "The Shelby family asserts its power in post-war Birmingham.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Paul Anderson"],
            ["tv_show_id" => 10, "season_id" => 8, "episode_name" => "Episode 2", "episode_number" => 2, "release_date" => "2013-09-19", "timeParts" => ["hour" => "01", "minute" => "00", "second" => "00"], "description" => "A dangerous rivalry threatens the Shelby empire.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Helen McCrory"],
            ["tv_show_id" => 10, "season_id" => 18, "episode_name" => "Black Star Day", "episode_number" => 1, "release_date" => "2014-10-02", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "The Shelbys expand their influence beyond Birmingham.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Tom Hardy"],
            ["tv_show_id" => 10, "season_id" => 18, "episode_name" => "Episode 2", "episode_number" => 2, "release_date" => "2014-10-09", "timeParts" => ["hour" => "01", "minute" => "05", "second" => "00"], "description" => "A dangerous alliance is tested by betrayal.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Tom Hardy"],
            ["tv_show_id" => 10, "season_id" => 28, "episode_name" => "The Noose", "episode_number" => 1, "release_date" => "2016-05-05", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Political pressure closes in on the Shelby family.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Paul Anderson"],
            ["tv_show_id" => 10, "season_id" => 28, "episode_name" => "Episode 2", "episode_number" => 2, "release_date" => "2016-05-12", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "The family faces its most dangerous enemy yet.", "directors" => "Steven Knight", "presenter" => "Cillian Murphy, Paul Anderson"],

            // The Boys (TV ID: 11) | Seasons: 1 (ID: 9), 2 (ID: 19), 3 (ID: 29)
            ["tv_show_id" => 11, "season_id" => 9, "episode_name" => "The Name of the Game", "episode_number" => 1, "release_date" => "2019-07-26", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "A vigilante group forms to fight corrupt superheroes.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Antony Starr"],
            ["tv_show_id" => 11, "season_id" => 9, "episode_name" => "Cherry", "episode_number" => 2, "release_date" => "2019-07-26", "timeParts" => ["hour" => "01", "minute" => "05", "second" => "00"], "description" => "The Boys uncover dark secrets behind superhero fame.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Antony Starr"],
            ["tv_show_id" => 11, "season_id" => 19, "episode_name" => "The Big Ride", "episode_number" => 1, "release_date" => "2020-09-04", "timeParts" => ["hour" => "01", "minute" => "08", "second" => "00"], "description" => "The team goes underground while facing new threats.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Jack Quaid"],
            ["tv_show_id" => 11, "season_id" => 19, "episode_name" => "Proper Preparation and Planning", "episode_number" => 2, "release_date" => "2020-09-04", "timeParts" => ["hour" => "01", "minute" => "12", "second" => "00"], "description" => "A new hero creates chaos for everyone.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Jack Quaid"],
            ["tv_show_id" => 11, "season_id" => 29, "episode_name" => "Payback", "episode_number" => 1, "release_date" => "2022-06-03", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Old grudges resurface with explosive consequences.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Antony Starr"],
            ["tv_show_id" => 11, "season_id" => 29, "episode_name" => "The Only Man in the Sky", "episode_number" => 2, "release_date" => "2022-06-03", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "The truth behind Vought’s secrets emerges.", "directors" => "Eric Kripke", "presenter" => "Karl Urban, Antony Starr"],

            // Narcos (TV ID: 12) | Seasons: 1 (ID: 10), 2 (ID: 20), 3 (ID: 30)
            ["tv_show_id" => 12, "season_id" => 10, "episode_name" => "Descenso", "episode_number" => 1, "release_date" => "2015-08-28", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "The rise of Pablo Escobar begins.", "directors" => "Chris Brancato", "presenter" => "Wagner Moura, Pedro Pascal"],
            ["tv_show_id" => 12, "season_id" => 10, "episode_name" => "The Sword of Simón Bolívar", "episode_number" => 2, "release_date" => "2015-08-28", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "Escobar’s empire grows through violence.", "directors" => "Chris Brancato", "presenter" => "Wagner Moura, Pedro Pascal"],
            ["tv_show_id" => 12, "season_id" => 20, "episode_name" => "Free at Last", "episode_number" => 1, "release_date" => "2016-09-02", "timeParts" => ["hour" => "01", "minute" => "25", "second" => "00"], "description" => "Escobar escapes prison, shocking authorities.", "directors" => "Chris Brancato", "presenter" => "Wagner Moura, Pedro Pascal"],
            ["tv_show_id" => 12, "season_id" => 20, "episode_name" => "Cambalache", "episode_number" => 2, "release_date" => "2016-09-02", "timeParts" => ["hour" => "01", "minute" => "22", "second" => "00"], "description" => "The hunt for Escobar intensifies.", "directors" => "Chris Brancato", "presenter" => "Wagner Moura, Pedro Pascal"],
            ["tv_show_id" => 12, "season_id" => 30, "episode_name" => "The Kingpin Strategy", "episode_number" => 1, "release_date" => "2017-09-01", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "New drug lords rise after Escobar’s fall.", "directors" => "Chris Brancato", "presenter" => "Pedro Pascal, Michael Peña"],
            ["tv_show_id" => 12, "season_id" => 30, "episode_name" => "The Cali KGB", "episode_number" => 2, "release_date" => "2017-09-01", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "The Cali Cartel dominates the drug trade.", "directors" => "Chris Brancato", "presenter" => "Pedro Pascal, Michael Peña"],

            // Dark (TV ID: 13) | Seasons: 1 (ID: 11), 2 (ID: 21), 3 (ID: 31)
            ["tv_show_id" => 13, "season_id" => 11, "episode_name" => "Secrets", "episode_number" => 1, "release_date" => "2017-12-01", "timeParts" => ["hour" => "01", "minute" => "05", "second" => "00"], "description" => "A child disappears in a quiet German town.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Oliver Masucci"],
            ["tv_show_id" => 13, "season_id" => 11, "episode_name" => "Lies", "episode_number" => 2, "release_date" => "2017-12-01", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "Families hide dark secrets as mysteries deepen.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Oliver Masucci"],
            ["tv_show_id" => 13, "season_id" => 21, "episode_name" => "Beginnings and Endings", "episode_number" => 1, "release_date" => "2019-06-21", "timeParts" => ["hour" => "01", "minute" => "15", "second" => "00"], "description" => "Time loops reveal painful truths.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Andreas Pietschmann"],
            ["tv_show_id" => 13, "season_id" => 21, "episode_name" => "Dark Matter", "episode_number" => 2, "release_date" => "2019-06-21", "timeParts" => ["hour" => "01", "minute" => "12", "second" => "00"], "description" => "The future of Winden becomes clearer.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Andreas Pietschmann"],
            ["tv_show_id" => 13, "season_id" => 31, "episode_name" => "Deja-Vu", "episode_number" => 1, "release_date" => "2020-06-27", "timeParts" => ["hour" => "01", "minute" => "10", "second" => "00"], "description" => "A new reality emerges with familiar faces.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Andreas Pietschmann"],
            ["tv_show_id" => 13, "season_id" => 31, "episode_name" => "The Survivors", "episode_number" => 2, "release_date" => "2020-06-27", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "The cycle approaches its end.", "directors" => "Baran bo Odar", "presenter" => "Louis Hofmann, Andreas Pietschmann"],

            // House of the Dragon: TV ID 14 | Seasons: 1(12), 2(22), 3(32)
            ["tv_show_id" => 14, "season_id" => 12, "episode_name" => "The Heirs of the Dragon", "episode_number" => 1, "release_date" => "2022-08-21", "timeParts" => ["hour" => "01", "minute" => "20", "second" => "00"], "description" => "The Targaryen dynasty faces internal conflict.", "directors" => "Ryan Condal", "presenter" => "Paddy Considine, Emma D’Arcy"],
            ["tv_show_id" => 14, "season_id" => 12, "episode_name" => "The Rogue Prince", "episode_number" => 2, "release_date" => "2022-08-28", "timeParts" => ["hour" => "01", "minute" => "18", "second" => "00"], "description" => "Prince Daemon challenges royal authority.", "directors" => "Ryan Condal", "presenter" => "Paddy Considine, Emma D’Arcy"],
            ["tv_show_id" => 14, "season_id" => 22, "episode_name" => "The Prince Who Was Promised", "episode_number" => 1, "release_date" => "2024-06-16", "timeParts" => ["hour" => "01", "minute" => "22", "second" => "00"], "description" => "Old prophecies resurface as war looms.", "directors" => "Ryan Condal", "presenter" => "Emma D’Arcy, Matt Smith"],
            ["tv_show_id" => 14, "season_id" => 22, "episode_name" => "Blood and Cheese", "episode_number" => 2, "release_date" => "2024-06-23", "timeParts" => ["hour" => "01", "minute" => "25", "second" => "00"], "description" => "A brutal act ignites the Dance of the Dragons.", "directors" => "Ryan Condal", "presenter" => "Emma D’Arcy, Matt Smith"],
            ["tv_show_id" => 14, "season_id" => 32, "episode_name" => "Fire and Blood", "episode_number" => 1, "release_date" => "2026-06-14", "timeParts" => ["hour" => "01", "minute" => "30", "second" => "00"], "description" => "Civil war engulfs the Targaryen dynasty.", "directors" => "Ryan Condal", "presenter" => "Emma D’Arcy, Matt Smith"],
            ["tv_show_id" => 14, "season_id" => 32, "episode_name" => "The Dragon’s Reckoning", "episode_number" => 2, "release_date" => "2026-06-21", "timeParts" => ["hour" => "01", "minute" => "28", "second" => "00"], "description" => "The fate of the Iron Throne is decided.", "directors" => "Ryan Condal", "presenter" => "Emma D’Arcy, Matt Smith"],
        ];

        foreach ($episodes as $episode) {
            $duration = $episode['timeParts']['hour'] . ':' .
                $episode['timeParts']['minute'] . ':' .
                $episode['timeParts']['second'];

            DB::table('tvshow_season_episodes')->insert([
                'tv_show_id' => $episode['tv_show_id'],
                'season_id' => $episode['season_id'],
                'episode_name' => $episode['episode_name'],
                'episode_number' => $episode['episode_number'],
                'release_date' => $episode['release_date'],
                'description' => $episode['description'],
                'directors' => $episode['directors'],
                'presenter' => $episode['presenter'],
                'resolution' => 'UHD',
                'scheduled_publishing' => true,
                'scheduled_time' => '2025-12-17 14:56:29',
                'expire_scheduled_time' => '2025-12-17 14:56:29',
                'publish_now' => true,
                'timeParts' => json_encode($duration),
                'publish_date' => '2025-12-17 18:26:34',
                'is_active' => true,
                'streaming_url' => 'https://cdn.flowplayer.com/demo/hls/sample/playlist.m3u8',
                'drm_type' => 'Pallycon',
                'drm_profile' => 1,
                'playback_token' => 1,
                'policy' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
