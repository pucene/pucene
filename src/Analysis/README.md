# Pucene: Analysis

Text analysis enables pucene to perform full-text search, where the search returns all relevant results rather than just 
exact matches.

If you search for `Quick fox jumps`, you probably want the document that contains 
`A quick brown fox jumps over the lazy dog`, and you might also want documents that contain related words like 
`fast fox` or `foxes leap`.

## Tokenization

Analysis makes full-text search possible through tokenization: breaking a text down into smaller chunks, called tokens.
In most cases, these tokens are individual words.

If you index the phrase `the quick brown fox jumps` as a single string and the user searches for `quick fox`, it isn’t
considered a match. However, if you tokenize the phrase and index each word separately, the terms in the query string
can be looked up individually. This means they can be matched by searches for `quick fox`, `fox brown`, or other
variations.

## Normalization

Tokenization enables matching on individual terms, but each token is still matched literally. This means:

* A search for `Quick` would not match `quick`, even though you likely want either term to match the other
* Although `fox` and `foxes` share the same root word, a search for `foxes` would not match `fox` or vice versa.
* A search for `jumps` would not match `leaps`. While they don’t share a root word, they are synonyms and have a similar
  meaning.

To solve these problems, text analysis can `normalize` these tokens into a standard format. This allows you to match
tokens that are not exactly the same as the search terms, but similar enough to still be relevant. For example:

* `Quick` can be lowercased: `quick`.
* `foxes` can be stemmed, or reduced to its root word: `fox`.
* `jump` and `leap` are synonyms and can be indexed as a single word: `jump`.

To ensure search terms match these words as intended, you can apply the same tokenization and normalization rules to the
query string. For example, a search for `Foxes leap` can be normalized to a search for `fox jump`.

## Customize text analysis

Text analysis is performed by an analyzer, a set of rules that govern the entire process.

Pucene includes a default analyzer, called the standard analyzer, which works well for most use cases right out of the
box.

If you want to tailor your search experience, you can choose a different [built-in analyzer](examples/standard.php#L33)
or even configure a custom one. A [custom analyzer](examples/custom.php#L45-L100) gives you control over each step of
the analysis process, including:

* Changes to the text before tokenization
* How text is converted to tokens
* Normalization changes made to tokens before indexing or search

## Examples

The directory [examples](examples) contains multiple files which contains examples how to use this library.

## Disclaimer

This text was highly inspired by: https://github.com/elastic/elasticsearch/blob/v8.5.3/docs/reference/analysis/overview.asciidoc
