# [Euradionantes.eu](http://www.euradionantes.eu) Web Platform


Built atop [Symfony2](http://symfony.com) & [Sonata Project](http://sonata-project.org).

Initiated [here, 2 years ago](https://github.com/DILL44/euradio), currently in a new WIP state,

## Installation

### Installing via GitHub

```bash
    $ git clone https://github.com/polypodes/EuradioNantes.eu.git
```

### Creating users

Check out [FOSUser documentation](https://github.com/FriendsOfSymfony/FOSUserBundle/blob/v1.3.6/Resources/doc/command_line_tools.md)

### Creating website

Command lines to create a website on a `euradionantes2015` local host, with `fr` locale.

```bash
php app/console  sonata:page:create-site --enabled=true --name=EuradioNantes --locale=fr --host=euradionantes2015 --relativePath=/ --enabledFrom=now --enabledTo="+20 years" --default=true 

Creating website with the following information :
  name : EuradioNantes
  site : http(s)://euradionantes2015
  enabled :  from Wed, 01 Jul 2015 10:05:09 +0200 => to Tue, 01 Jul 2025 10:05:09 +0200

Confirm site creation ?yes

Site created !

You can now create the related pages and snapshots by running the followings commands:
  php app/console sonata:page:update-core-routes --site=1
  php app/console sonata:page:create-snapshots --site=1
``

## License

MIT Licensed.

You can find a copy of this software here: [polypodes/EuradioNantes.eu](https://github.com/polypodes/EuradioNantes.eu)

