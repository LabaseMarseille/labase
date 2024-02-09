FROM carpe-koi.univ-amu.fr:5000/linux-22.04-php8.2-amd64
RUN useradd dev-php -u 1001 --create-home -c 'Utilisateur dvpt docker' -G www-data,adm -s /bin/bash
