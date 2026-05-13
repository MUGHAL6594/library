<?php

class Book {
  private $id;
  private $title;
  private $author;
  private $genre;
  private $pages;
  private $rating;
  private $description;
  private $available;
  private $coverImage;

  public function __construct($id, $title, $author, $genre, $pages, $rating, $description, $available, $coverImage = null) {
    $this->id          = $id;
    $this->title       = $title;
    $this->author      = $author;
    $this->genre       = $genre;
    $this->pages       = $pages;
    $this->rating      = $rating;
    $this->description = $description;
    $this->available   = $available;
    $this->coverImage  = $coverImage;
  }

  public function getId() { return $this->id; }
  public function getTitle() { return $this->title; }
  public function getAuthor() { return $this->author; }
  public function getGenre() { return $this->genre; }
  public function getPages() { return $this->pages; }
  public function getRating() { return $this->rating; }
  public function getDescription() { return $this->description; }
  public function getAvailable() { return $this->available; }
  public function getCoverImage() { return $this->coverImage; }
}
