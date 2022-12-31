# Pucene

A pure PHP implementation of lucene index.

## Description

Pucene is a pure php-library which basically implements a lucene index. Lucene is a very well documented library - but
pucene is not a direct copy - it will define some additional things and will do some things different than the original
library.

## Components

This repository is the monorepository of the pucene project and contains following components:

* [Analysis](packages/analysis): Text analysis is the process of converting unstructured text, like the body of an email
  or a product description, into a structured format that’s optimized for search.
* [Dbal Driver](packages/dbal-driver): The driver implementation built ontop of doctrine dbal.
* [Index](packages/index): The index analyses, stores and searches for documents. It uses different other packages from
  this project.
* [Seal Adapter](packages/seal-adapter): Adapter package that glues pucene into SEAL universe.
