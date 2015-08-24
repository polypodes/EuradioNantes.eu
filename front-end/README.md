# Front-end

This folder contains all the assets and stuff for the front-end part of euradionantes.eu website

## Requirements :


**nodejs** : `brew install node` 

or via **curl** :

```
curl "https://nodejs.org/dist/latest/node-${VERSION:-$(wget -qO- https://nodejs.org/dist/latest/ | sed -nr 's|.*>node-(.*)\.pkg</a>.*|\1|p')}.pkg" > "$HOME/Downloads/node-latest.pkg" && sudo installer -store -pkg "$HOME/Downloads/node-latest.pkg" -target "/"

```

scss_lint via gem :

`gem install scss_lint`


## How to install this project


`npm install`


## How to build for developement

If not already done, set the Environment variable to developement: `export NODE_ENV=development` 

then 

`npm run build`


## How to build for production (uglify, minify…)

If not already done, set the Environment variable to production: `export NODE_ENV=production` 

then

`npm run build`


## How to serve this project


`npm start`


## How to copy front-end assets to Symfony web directory

`npm run cms`


## Lint & Test

This repo use git pre-commit hook, if test and lint failed commit is aborted until you fix lint/test issues

List of lint/test tools:

- eslint
- jscs
- scss-lint

### How to execute test/lint

`npm test`