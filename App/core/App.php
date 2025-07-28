<?php
namespace App\Core;

class App
{
    private static array $dependencies = [];

    /**
     * Charge les dépendances depuis le fichier services.yml
     */
    private static function loadDependencies(): void
    {
        if (!empty(self::$dependencies)) {
            return;
        }

        $configPath = __DIR__ . '/../../config/services.yml';
        
        if (!file_exists($configPath)) {
            throw new \Exception("Le fichier de configuration '$configPath' n'existe pas.");
        }

        $content = file_get_contents($configPath);
        $lines = explode("\n", $content);
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorer les lignes vides et commentaires
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            // Section (se termine par : et pas d'espaces)
            if (substr($line, -1) === ':' && strpos($line, ' ') === false && strpos($line, '-') === false) {
                $currentSection = str_replace(':', '', $line);
                self::$dependencies[$currentSection] = [];
                continue;
            }

            // Élément de liste (commence par  - )
            if (substr($line, 0, 2) === '- ' || substr($line, 0, 4) === '  - ') {
                // Nettoyer la ligne pour extraire la classe
                $value = str_replace(['- ', '  - '], '', $line);
                $value = trim($value);
                
                if ($currentSection && !empty($value)) {
                    self::$dependencies[$currentSection][] = $value;
                }
            }
        }
    }

    /**
     * Récupère une instance de la classe spécifiée, en utilisant l'injection de dépendances
     * @param string $className Le nom de la classe à récupérer
     * @return object L'instance de la classe demandée
     * @throws \Exception Si la classe n'est pas trouvée ou si une dépendance ne peut pas être résolue
     */
    public static function getDependencie(string $className)
    {
        self::loadDependencies();

        foreach (self::$dependencies as $category => $classes) {
            foreach ($classes as $fullClassName) {
                $shortName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);

                if ($shortName === $className) {
                    if (!class_exists($fullClassName)) 
                    {
                        throw new \Exception('La classe "' . $fullClassName . '" n\'existe pas.');
                    }

                    if (method_exists($fullClassName, 'getInstance')) 
                    {
                        return $fullClassName::getInstance();
                    }

                    // Injection de dépendances automatique par réflexion
                    $reflection = new \ReflectionClass($fullClassName);
                    $constructor = $reflection->getConstructor();
                    if ($constructor) 
                    {
                        $params = $constructor->getParameters();
                        $dependencies = [];
                        foreach ($params as $param) {
                            $type = $param->getType();
                            if ($type && !$type->isBuiltin()) {
                                $depClass = $type->getName();
                                // On récupère le nom court de la classe de dépendance
                                $depShortName = substr($depClass, strrpos($depClass, '\\') + 1);
                                $dependencies[] = self::getDependencie($depShortName);
                            } else if ($param->isDefaultValueAvailable()) {
                                $dependencies[] = $param->getDefaultValue();
                            } else {
                                throw new \Exception('Impossible de résoudre la dépendance pour le paramètre "' . $param->getName() . '" dans "' . $fullClassName . '".');
                            }
                        }
                        return $reflection->newInstanceArgs($dependencies);
                    } else
                    {
                        return $reflection->newInstance();
                    }
                }
            }
        }

        throw new \Exception("La classe '$className' n'a pas été trouvée dans les dépendances.");
    }
}
