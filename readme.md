## Paleidimas

1. Įsidiegti [Homestead](http://laravel.com/docs/master/homestead) arba kitą serverį.
2. Įsidiegti ffmpeg. Instrukcijos ant ubuntu sistemos:

        sudo add-apt-repository ppa:kirillshkrogalev/ffmpeg-next
        sudo apt-get update
        sudo apt-get install ffmpeg
        
3. Įsidiegti [elixir](http://laravel.com/docs/master/elixir).


## Kaip sukelti atnaujinimus?

Yra 2 branch'ai `master` ir `develop`. Pakeitimus daryti tik develop branche ir visus atnaujinimus pushint tik į develop branch. Jei yra koks didesnis atnaujinimas susikurti tam atskirą branchą ir vėliau per pull request sujungti į develop. Kai viskas padaryta ir ištestuota sukurti pull request ir sujungti pakeitimus į master.

Viską sukėlus į Git prisijungti prie serverio ir puslapio direktorijoj (`~/domains/speechtotextservice.com`) paleisti šias komandas:

    php artisan down
    git pull
    php artisan migrate # Jei reikia
    composer.phar update # Jei reikia
    php artisan up