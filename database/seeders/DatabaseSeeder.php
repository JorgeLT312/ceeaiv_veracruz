<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            AreasSeeder::class,
            UserSeeder::class,
            TipoSolicitanteSeeder::class,
            SexoSeeder::class,
            TipoVictimaSeeder::class,
            AmbitoDependenciaSeeder::class,
            AmbitoCompetenciaSeeder::class,
            TipoAutoridadNombreSeeder::class,
            DocumentoProbatorioSeeder::class,
            DocProbatorioIdentidadSeeder::class,
            SituacionMigratoriaSeeder::class,
            IdentidadNacSeeder::class,
            EntidadFederativaSeeder::class,
            MunicipioSeeder::class,
            TipoTelefonoSeeder::class,
            PaisSeeder::class,
            DaniosSufridosSeeder::class,
            ViolacionDHSeeder::class,
            ParentescoSeeder::class,
            EnfoqueConsideracionSeeder::class,
            EnfoqueTipoDiscapacidadSeeder::class,
            EnfoqueGradoDependenciaSeeder::class,
            EnfoquePobIndigenaSeeder::class,
            EnfoqueIdiomaSeeder::class,
            EnfoqueViolenciaMujerSeeder::class,
            EnfoqueTipoMedioSeeder::class,
            EnfoqueTipoInstSeeder::class,
            TipoMedidasSeeder::class,
            MedidasSeeder::class,
            DelitoSeeder::class,
            EstadoCivilSeeder::class,
            NotificacionesSeeder::class,
        ]);
    }
}
