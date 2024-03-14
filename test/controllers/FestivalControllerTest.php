<?php

namespace controllers;

use PHPUnit\Framework\TestCase;

class FestivalControllerTest extends TestCase
{
    public function testAll()
    {
        // Given a festival controller
        // When we call the all method
        // Then we get the list of all festivals
    }

    public function testListFavoriteFestival()
    {
        // Given a festival controller
        // When we call the listFavoriteFestival method
        // Then we get the list of all favorite festivals
    }

    public function testDetailsFestival()
    {
        // Given a festival controller
        // When we call the detailsFestival method
        // Then we get the details of a festival
    }

    public function testDetailsFestivalNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with an unknown festival
        // Then we get an error
    }

    public function testDetailsFestivalWithSpectacles()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles
        // Then we get the details of the festival and its spectacles
    }

    public function testDetailsFestivalWithSpectaclesNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles
        // Then we get the details of the festival
    }

    public function testDetailsFestivalWithSpectaclesWithCategories()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles with categories
        // Then we get the details of the festival and its spectacles with categories
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles with categories
        // Then we get the details of the festival and its spectacles
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatrice()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles with categories and equipe organisatrice
        // Then we get the details of the festival and its spectacles with categories and equipe organisatrice
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles with categories and equipe organisatrice
        // Then we get the details of the festival and its spectacles with categories
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateur()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles with categories, equipe organisatrice and utilisateur
        // Then we get the details of the festival and its spectacles with categories, equipe organisatrice and utilisateur
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateurNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles with categories, equipe organisatrice and utilisateur
        // Then we get the details of the festival and its spectacles with categories and equipe organisatrice
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateurWithSpectacle()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles with categories, equipe organisatrice, utilisateur and spectacle
        // Then we get the details of the festival and its spectacles with categories, equipe organisatrice, utilisateur and spectacle
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateurWithSpectacleNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles with categories, equipe organisatrice, utilisateur and spectacle
        // Then we get the details of the festival and its spectacles with categories, equipe organisatrice and utilisateur
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateurWithSpectacleWithCategories()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has spectacles with categories, equipe organisatrice, utilisateur, spectacle and categories
        // Then we get the details of the festival and its spectacles with categories, equipe organisatrice, utilisateur, spectacle and categories
    }

    public function testDetailsFestivalWithSpectaclesWithCategoriesWithEquipeOrganisatriceWithUtilisateurWithSpectacleWithCategoriesNotFound()
    {
        // Given a festival controller
        // When we call the detailsFestival method with a festival that has no spectacles with categories, equipe organisatrice, utilisateur, spectacle and categories
        // Then we get the details of the festival and its spectacles with categories, equipe organisatrice, utilisateur and spectacle
    }

    public function testDetailsShow()
    {
        // Given a festival controller
        // When we call the detailsShow method
        // Then we get the details of a show
    }

    public function testDetailsShowNotFound()
    {
        // Given a festival controller
        // When we call the detailsShow method with an unknown show
        // Then we get an error
    }



}