# Getting Started

- [ ] run: `git clone https://github.com/ArtemTancyura/Lektorium-blog.git`
- [ ] go to progect folder: `cd Lektorium-blog`
- [ ] edit .env
- [ ] run:
    `composer install`  
    `bin/console d:d:c`  
    `bin/console d:s:u -f`  
    `bin/console doctrine:fixtures:load`  
     for making admin  
    `bin/console app:admin-create -n "name" -s "surname" -e "email" -p "password"`  
     if you have a bad mood  
    `bin/console app:want-happy`  
      
# Users:  
######    admin  
        (from your console command)  
######    blogger  
        blog@blog.blog  
        blogger  
######    user  
        user@user.user  
        user  
