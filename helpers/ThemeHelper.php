<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\helpers;

use humhub\components\Theme;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\themebuilder\Module;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * Class ThemeHelper
 * @package humhub\modules\themebuilder\helpers
 */
class ThemeHelper extends \humhub\modules\ui\view\helpers\ThemeHelper
{
    /**
     * Make sure the theme is using the latest version
     *
     * @param $theme Theme
     */
    public static function upgrade($theme)
    {
        $basePath = $theme->getBasePath() . '/views';

        $isEnterprise = false;
        foreach ($theme->getParents() as $t) {
            if ($t->name == 'enterprise') {
                $isEnterprise = true;
            }
        }

        $views = [];

        // Remove old enterprise view files
        $views['d41d8cd98f00b204e9800998ecf8427e'] = '/layouts/head.php';

        if ($isEnterprise) {
            $views['e35f851cb9ea97c69cb0758c48c4d92e'] = '/widgets/topNavigation.php';
            $views['904921629e6ae89c005b03fcb3e024db'] = '/ui/menu/widgets/views/dropdown-menu.php';
            $views['b327ae9b1ab1a4d576917ff9ff4a383a'] = '/ui/menu/widgets/views/left-navigation.php';
            $views['2450f220d82ba3c61ad76789216b7c77'] = '/ui/menu/widgets/left-navigation.php';
            $views['75fc2935682e25519369a1cd56cf9d81'] = '/tour/widgets/guide_interface.php';
            $views['ccccb39862468599ed07bd5b8a885b5e'] = '/tour/widgets/guide_spaces_old.php';
            $views['9ead2d34fdedb1ac837346a1d0a0def2'] = '/spacetype/widgets/spaceChooser.php';
            $views['773734be364c2465c99d0275a511ffa4'] = '/spacetype/widgets/spaceChooserItem.php';
            $views['4dc6d10b4b028d396637b2969892e0a2'] = '/space/space/_layout.php';
            $views['293dfbfb10eb90e1415628d7da82fb97'] = '/space/widgets/followButton.php';
            $views['255f6ccd4c6333b3db0d14b67f108098'] = '/space/widgets/inviteButton.php';
            $views['7efc4bb03457f76fccc50a63e8259469'] = '/space/widgets/membershipButton.php';
            $views['7efc4bb03457f76fccc50a63e8259469'] = '/space/widgets/membershipButton.php';
            $views['194557589ef17c82cd125401bb73e4c6'] = '/search/widgets/searchMenu.php';
            $views['c62681733bba5b17e9b667ac8d5a8f13'] = '/search/widgets/searchMenu.php';
            $views['3674820343a7182d6ff9bd7eba98aa8f'] = '/layouts/head.php';
            $views['116fadea9e955a011b34ce1bc5c9cca6'] = '/layouts/main.php';
            $views['8b4dabf564cc5852a5e639151737a87f'] = '/dashboard/dashboard/index.php';
            $views['a3a1b5300fad1352dfd55d23b4e9bf27'] = '/dashboard/dashboard/index_guest.php';

            if (version_compare(Yii::$app->version, '1.4', '>=')) {
                $views['8ace7e046f687d3607e41887a981bcba'] = '/layouts/head.php';
                $views['fcbd079dcdc32b54ed6d1c85aafd93e6'] = '/widgets/topNavigation.php';
                $views['fcf22dbb9c0b82df23ec653d4386275c'] = '/tour/widgets/guide_interface.php';
                $views['eb845d33e5e22a7e10d9666b787319d3'] = '/spacetype/widgets/spaceChooser.php';
                $views['479d1b437d9c1bfa81f054bdab28830f'] = '/spacetype/widgets/spaceChooserItem.php';
                $views['aea204098c5bbf5671d6d6c17932858f'] = '/space/space/_layout.php';
                $views['f9b7f9954b1562417bc382581538fd92'] = '/space/widgets/followButton.php';
                $views['27da4b381b116eee951dda6ea335ba79'] = '/space/widgets/inviteButton.php';
                $views['f49469d9d9e715ae493a82767a77c80d'] = '/space/widgets/membershipButton.php';
                $views['c62681733bba5b17e9b667ac8d5a8f13'] = '/search/widgets/searchMenu.php';
                $views['e9b37d967c56909fbaa4fb82075cb238'] = '/dashboard/dashboard/index.php';
                $views['d92f71f14bbe8261c2470abf1315babf'] = '/dashboard/dashboard/index_guest.php';
                $views['ceb63adb2c9458275fed9dff9d1e753f'] = '/widgets/leftNavigation.php';
                $views['94eb54c960315921e90ea31b1eb9f7f4'] = '/widgets/dropdownNavigation.php';
                $views['186859ee061d7e170f74c8ec5a695f1c'] = '/tour/widgets/guide_spaces.php';
                $views['3588a4fe95254ca71f34ac8798815e13'] = '/layouts/main.php';
            }
        }

        foreach ($views as $md5 => $f) {
            if (is_file($basePath . $f) && md5_file($basePath . $f) === $md5) {
                unlink($basePath . $f);
                Yii::warning("Removed deprecated file: " . $f . " from theme " . $theme->name);
            }
        }

        static::removeEmptySubFolders($basePath);
        if (!is_dir($basePath)) {
            mkdir($basePath);
        }

    }

    private static function removeEmptySubFolders($path)
    {
        $empty = true;
        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            if (is_dir($file)) {
                if (!static::removeEmptySubFolders($file)) $empty = false;
            } else {
                $empty = false;
            }
        }
        if ($empty) rmdir($path);
        return $empty;
    }

}