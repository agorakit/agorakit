# Contribute to the documentation

Documentation is written in markdown and the website is statically built using [Mkdocs material](https://squidfunk.github.io/mkdocs-material/)


## How to contribute ?
- Create a Github account
- Fork the Agorakit repository
- Edit the documentation files in the /docs/ directory
- Create a pull request 

NOTE: I find that using VS code to work on code and markdown files a simple enough option for most people.

## How to build the docs ?
Install mkdocs material and a few plugins. 

```
pip install mkdocs-material
pip install mkdocs-mdpo-plugin
pip install markdown-callouts
```

Then start the built-in webserver to preview your work on the documentation.
```
cd docs
mkdocs serve
```

See https://squidfunk.github.io/mkdocs-material/getting-started/ for more informations on how the documentation builder works.



