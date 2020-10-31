<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesTableSeeder::class,
            LanguagesTableSeeder::class,
            ThemesTableSeeder::class,
            UserPreferencesTableSeeder::class,
            UserRolesTableSeeder::class,
            UserStatusesTableSeeder::class,
            UsersTableSeeder::class,
            MovieRatingsTableSeeder::class,
            AlbumsTableSeeder::class,
            MusicTableSeeder::class,
            MoviesTableSeeder::class,
            MovieGenresTableSeeder::class,
            MusicGenresTableSeeder::class,
            AwardsTableSeeder::class,
            ContributorsTableSeeder::class,
            ContributorRolesTableSeeder::class,
            MoviesContributorsAssociationTableSeeder::class,
            MusicContributorsAssociationTableSeeder::class,
            MoviesGenresAssociationTableSeeder::class,
            MusicGenresAssociationTableSeeder::class,
            MoviesAwardsAssociationTableSeeder::class,
            MusicAwardsAssociationTableSeeder::class,
            CompaniesTableSeeder::class,
            CompanyRolesTableSeeder::class,
            MoviesCompaniesAssociationTableSeeder::class,
            MusicCompaniesAssociationTableSeeder::class,
            ReviewsTableSeeder::class,
            MovieExtrasTableSeeder::class,
        ]);
    }
}
