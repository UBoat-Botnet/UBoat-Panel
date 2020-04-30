# UBoat-Panel

Administration panel for the HTTP botnet **UBoat** - https://github.com/Souhardya/UBoat/

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

- [PHP](https://www.php.net/) >= 7.0
- [pdo](https://www.php.net/manual/en/pdo.installation.php)
- [pdo_mysql](https://www.php.net/manual/en/ref.pdo-mysql.php)
- [mcrypt](https://www.php.net/manual/en/mcrypt.installation.php)
- [json](https://www.php.net/manual/en/json.installation.php)
- [gd](https://www.php.net/manual/en/image.installation.php) (w/ FreeType and PNG support)

### Installing

Clone this repository

```
git clone https://github.com/matricali/UBoat-Panel.git
```

Build and start the containers using [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/)

```
cd UBoat-Panel/
docker-compose up -d
```

It will set up an official PHP image plus necessary extensions and an official MariaDB container.

Then you can navigate panel at http://localhost/login

Once you login you'll get something like this

![](https://preview.ibb.co/eVdWeT/Screenshot_7.png)

## Deployment

Additional notes to deploy de panel on your own server

### Change database configuration

First, change the database connection in [config.php](src/config/config.php) pointing to your MySQL or MariaDB database.

### Set up your virtual host

You can use your preferred webserver

#### Configure Apache virtual host

```
Options +SymLinksIfOwnerMatch
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

#### Configure Nginx virtual host
```
location / {
    try_files $uri /index.php?_url=$uri&$args;
}
```

## Built With

* [Bootstrap 3](https://getbootstrap.com/docs/3.4/) - The most popular HTML, CSS, and JS framework.
* [jQuery](https://jquery.com/) - A fast, small, and feature-rich JavaScript library.
* [Chart.js](https://chartjs.org/) - Simple yet flexible JavaScript charting.
* [jVectorMap](https://jvectormap.com/) - A vector-based component for interactive geography-related data visualization

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/matricali/UBoat-Panel/tags).

## Authors

* **Souhardya Sardar** - *UBoat's author* - [Souhardya](https://github.com/Souhardya) [@malpwn](https://twitter.com/malpwn)
* **Tuhinshubhra** - *Initial work* - [r3dhax0r](https://github.com/r3dhax0r) [@r3dhax0r](https://twitter.com/r3dhax0r)
* **Jorge Matricali** - *Current maintainer* - [matricali](https://github.com/matricali)

See also the list of [contributors](https://github.com/matricali/UBoat-Panel/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Disclaimer

**This project should be used for authorized testing or educational purposes only.**

**The main objective behind creating this offensive project was to aid security researchers and to enhance the understanding of commercial HTTP loader style botnets.
We hope this project helps to contribute to the malware research community and people can develop efficient countermeasures.**

**Usage of UBoat without prior mutual consistency can be considered as an illegal activity. It is the final user's responsibility to obey all applicable local, state, and federal laws. Authors assume no liability and are not responsible for any misuse or damage caused by this program.**
