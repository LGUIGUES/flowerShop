-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 06 mai 2022 à 15:13
-- Version du serveur :  5.7.24
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `flower_shop`
--

-- --------------------------------------------------------

--
-- Structure de la table `addresse`
--

CREATE TABLE `addresse` (
  `id_address` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `addresse` varchar(255) NOT NULL,
  `address_comp` varchar(255) DEFAULT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `addresse`
--

INSERT INTO `addresse` (`id_address`, `id_user`, `alias`, `lastname`, `firstname`, `company`, `addresse`, `address_comp`, `zip_code`, `city`, `country`, `phone`) VALUES
(1, 1, 'Perso', 'Doo', 'John', '-', 'Rue de la liberté', NULL, '75000', 'Paris', 'France', '0102030405');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL,
  `id_category` int(10) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `title_category` varchar(255) DEFAULT NULL,
  `text_category` text,
  `category_product` int(11) DEFAULT NULL,
  `name_img_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `id_category`, `category_name`, `title_category`, `text_category`, `category_product`, `name_img_category`) VALUES
(1, 1000, 'Naissance', 'Naissance', '<p>Une galerie de photos des produits confectionn&eacute;s pour des naissances.</p>\r\n', 0, '1000_naissance.jpg'),
(2, 2000, 'Mariage', 'Mariage', '<p>Une galerie de photos des produits confectionn&eacute;s pour des mariages.</p>\r\n', 0, '2000_mariage.jpg'),
(3, 3000, 'Deuil', 'Deuil', '<p>Une galerie de photos des produits confectionn&eacute;s pour des deuils.</p>\r\n', 0, '3000_deuil.jpg'),
(4, 4000, 'Compositions', 'Compositions', '<p>Une galerie de photos des compositions confectionn&eacute;s pour diverses occasions.</p>\r\n', 0, '4000_compositions.jpg'),
(5, 5000, 'Cadeaux', 'Cadeaux', '<p>Les produits cadeaux que nous vendons dans notre boutique.</p>\r\n', 1, '5000_cadeaux.jpg'),
(6, 6000, 'Décoration', 'Décoration', '<p>Les produits d&eacute;coration que nous vendons dans notre boutique.</p>\r\n', 1, '6000_decoration.jpg'),
(7, 7000, 'Galerie Photos', 'Retrouvez toutes nos photos', 'Découvrez toutes nos réalisations dans cette galerie photos.', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `content`
--

CREATE TABLE `content` (
  `id_page` int(10) NOT NULL,
  `title_content` varchar(255) DEFAULT NULL,
  `text_content` text,
  `img_content` varchar(255) DEFAULT NULL,
  `deletable` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `id_location` int(10) DEFAULT '0',
  `date_add` date DEFAULT NULL,
  `date_upd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `content`
--

INSERT INTO `content` (`id_page`, `title_content`, `text_content`, `img_content`, `deletable`, `active`, `id_location`, `date_add`, `date_upd`) VALUES
(1, 'Conditions Générales de Ventes', '<p><strong>Conditions g&eacute;n&eacute;rales de vente</strong></p>\r\n\r\n<p>Les pr&eacute;sentes conditions g&eacute;n&eacute;rales de vente s&#39;appliquent &agrave; toutes les ventes conclues sur le site Internet https://www.flowerShop.fr, sous r&eacute;serve des conditions particuli&egrave;res indiqu&eacute;es dans la pr&eacute;sentation des produits.</p>\r\n\r\n<p><em><strong>ARTICLE 1&nbsp;: Mentions obligatoires</strong></em></p>\r\n\r\n<p>Le site Internet https://www.flowerShop.fr est un service de&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>Nom Pr&eacute;nom - FlowerShop</li>\r\n	<li>Adresse &ndash; 75000 Paris - France</li>\r\n	<li>Siret 00000000000000 &ndash; RCS de Ville du RCS</li>\r\n	<li>contact@email.fr</li>\r\n	<li>T&eacute;l&eacute;phone 01 02 03 04 05</li>\r\n</ul>\r\n\r\n<p><strong><em>ARTICLE 2&nbsp;: Caract&eacute;ristiques essentielles des produits et services vendus</em></strong></p>\r\n\r\n<p>Le site Internet https://www.flowerShop vend&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>D&eacute;corations, bougies parfum&eacute;es, plantes, bouquets, vases et accessoires.</li>\r\n</ul>\r\n\r\n<p>Le client d&eacute;clare avoir pris connaissance et avoir accept&eacute; les conditions g&eacute;n&eacute;rales de vente ant&eacute;rieurement &agrave; la passation de sa commande. La validation de votre commande vaut donc pour acceptation des conditions g&eacute;n&eacute;rales de vente.</p>\r\n\r\n<p><em><strong>ARTICLE 3&nbsp;: Prix</strong></em></p>\r\n\r\n<p>Les prix de nos produits sont indiqu&eacute;s euros toutes taxes comprises (TTC).</p>\r\n\r\n<p>En cas de commande livr&eacute;e dans un pays autre que la France m&eacute;tropolitaine, le client est l&#39;importateur des produits qu&rsquo;il ach&egrave;te. Pour tous les produits exp&eacute;di&eacute;s hors des collectivit&eacute;s d&rsquo;outre-mer ou de l&rsquo;Union europ&eacute;enne, la facture est &eacute;tablie sur le prix hors taxes. Le client est seul responsable des d&eacute;clarations et paiements de tout droit de douane ou autre taxe susceptibles d&#39;&ecirc;tre exigibles dans son pays.</p>\r\n\r\n<p><em><strong>ARTICLE 4&nbsp;: D&eacute;lai de disponibilit&eacute; des produits</strong></em></p>\r\n\r\n<p>Les produits sur notre site sont disponibles dans la limite du stock au moment de la commande.</p>\r\n\r\n<p>Si vous avez command&eacute; un produit indisponible post&eacute;rieurement &agrave; la validation de votre commande, vous en serez imm&eacute;diatement inform&eacute;. Nous proc&eacute;derons &agrave; l&#39;annulation de votre achat. Si la somme avait d&eacute;j&agrave; &eacute;t&eacute; d&eacute;bit&eacute;e, vous serez imm&eacute;diatement rembours&eacute;.</p>\r\n\r\n<p><em><strong>ARTICLE 5&nbsp;: Commande</strong></em></p>\r\n\r\n<p>Vous avez la possibilit&eacute; de commander nos produits directement sur notre site internet ou par t&eacute;l&eacute;phone au 01 02 03 04 05 du mardi au samedi, de 9h00 &agrave; 12h30 et de 14h30 &agrave; 19h.</p>\r\n\r\n<p>Pour passer une commande sur notre site, choisissez vos articles et ajoutez-les au panier. Validez le contenu de votre panier.</p>\r\n\r\n<ul>\r\n	<li>Si vous poss&eacute;dez d&eacute;j&agrave; un compte client sur notre site, veuillez vous identifier.</li>\r\n	<li>Si vous ne poss&eacute;dez pas de compte client sur notre site, veuillez en cr&eacute;er un.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li>Choisissez le retrait en magasin.</li>\r\n	<li>Valider votre panier.</li>\r\n	<li>Vous pouvez suivre l&rsquo;avancement de votre commande dans votre espace personnalis&eacute;.</li>\r\n	<li>Vous &ecirc;tes inform&eacute; par sms et email de la disponibilit&eacute; de votre commande.</li>\r\n	<li>Le r&egrave;glement se fait en magasin.</li>\r\n</ul>\r\n\r\n<p>Le transfert de propri&eacute;t&eacute; du produit n&#39;aura lieu qu&#39;au paiement complet de votre commande.</p>\r\n\r\n<p><em><strong>ARTICLE 6&nbsp;: Livraison</strong></em></p>\r\n\r\n<ul>\r\n	<li>Nous vous proposons un seul type de livraison&nbsp;: Le retrait en magasin.</li>\r\n</ul>\r\n\r\n<p><em><strong>ARTICLE 7 : Modalit&eacute;s de paiement</strong></em></p>\r\n\r\n<ul>\r\n	<li>Plusieurs moyens de paiement sont accept&eacute;s en magasin. En tant que client, vous avez la possibilit&eacute; de payer par&nbsp;: carte bancaire, esp&egrave;ce, ch&egrave;que.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li>Nous acceptons les paiements par carte bleue / visa / mastercard /eurocard.</li>\r\n	<li>Nous acceptons les paiements par ch&egrave;que. Nous n&#39;autorisons que les ch&egrave;ques &eacute;mis par une banque situ&eacute;e en France m&eacute;tropolitaine.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li>D&egrave;s la passation de votre commande, nous proc&eacute;dons &agrave; la r&eacute;servation de votre produit pendant un d&eacute;lai de 24 heures. En l&#39;absence de paiement dans le d&eacute;lai indiqu&eacute;, nous nous verrons dans l&#39;obligation d&#39;annuler votre commande. &Eacute;ventuellement&nbsp;:</li>\r\n</ul>\r\n\r\n<p><em><strong>ARTICLE 8 : Droit de r&eacute;tractation</strong></em></p>\r\n\r\n<ul>\r\n	<li>Le droit de r&eacute;tractation ne s&#39;applique pas pour les produits r&eacute;alis&eacute;s selon vos indications sp&eacute;cifiques et pour les produits personnalis&eacute;s. Sont &eacute;galement exclus du droit de r&eacute;tractation les produits qui sont par nature p&eacute;rissables, qui ne peuvent &ecirc;tre r&eacute;exp&eacute;di&eacute;s (comme les t&eacute;l&eacute;chargements) ou qui peuvent se d&eacute;t&eacute;riorer.</li>\r\n</ul>\r\n\r\n<p><em><strong>ARTICLE 9 : Garantie et droit de retour du produit (vice cach&eacute; ou d&eacute;fectuosit&eacute;)</strong></em></p>\r\n\r\n<p>Vous b&eacute;n&eacute;ficiez de la garantie l&eacute;gale de conformit&eacute; et donc d&#39;une application de l&#39;article L211-4 du Code de la consommation qui dispose que&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>&laquo;&nbsp;Le vendeur est tenu de livrer un bien conforme au contrat et r&eacute;pond des d&eacute;fauts de conformit&eacute; existant lors de la d&eacute;livrance.&nbsp;&raquo;</li>\r\n	<li>&laquo;&nbsp;Il r&eacute;pond &eacute;galement des d&eacute;fauts de conformit&eacute; r&eacute;sultant de l&#39;emballage, des instructions de montage ou de l&#39;installation lorsque celle-ci a &eacute;t&eacute; mise &agrave; sa charge par le contrat ou a &eacute;t&eacute; r&eacute;alis&eacute;e sous sa responsabilit&eacute;.&nbsp;&raquo;</li>\r\n</ul>\r\n\r\n<p>La garantie est applicable &agrave; nos produits neufs ayant un caract&egrave;re d&eacute;fectueux (absence de fonctionnalit&eacute;, produit impropre &agrave; l&#39;usage auquel vous pouvez vous attendre, absence des caract&eacute;ristiques pr&eacute;sent&eacute;es en ligne, dysfonctionnement partiel ou total du produit, panne du produit) et ce, m&ecirc;me en l&#39;absence de garantie contractuelle.</p>\r\n\r\n<p>Un d&eacute;faut de conformit&eacute; peut appara&icirc;tre jusqu&#39;&agrave; 6&nbsp;mois apr&egrave;s la transaction. Son existence est pr&eacute;sum&eacute;e au jour de la d&eacute;livrance du bien et vous n&#39;avez pas &agrave; d&eacute;montrer l&#39;existence du d&eacute;faut de conformit&eacute;.</p>\r\n\r\n<p>Dans le cas d&#39;un d&eacute;faut de conformit&eacute;, nous nous engageons &agrave;&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>remplacer le produit&nbsp;;</li>\r\n	<li>r&eacute;parer le produit dans un d&eacute;lai de 30 jours et sans frais.</li>\r\n</ul>\r\n\r\n<p>En cas de d&eacute;faillance dans l&#39;ex&eacute;cution de notre obligation ou en cas de d&eacute;fectuosit&eacute; majeure, nous nous engageons &agrave; vous rembourser la totalit&eacute; du prix vers&eacute; ou une partie du prix si vous souhaitez conserver le produit.</p>\r\n\r\n<p>La pr&eacute;somption du d&eacute;faut de conformit&eacute; ne s&#39;applique plus s&#39;il appara&icirc;t apr&egrave;s 6 mois suivants la vente et avant 2 ans. Vous devez rapporter la preuve de la d&eacute;fectuosit&eacute; de notre produit.</p>\r\n\r\n<p>En cas de vice cach&eacute; de votre produit, vous b&eacute;n&eacute;ficiez de la garantie l&eacute;gale des vices cach&eacute;s des articles 1641 &agrave; 1649 du Code civil. Elle s&#39;applique &agrave; tous nos produits neufs. Cette garantie s&#39;applique lorsque le vice rend le produit impropre &agrave; l&#39;usage ou lorsqu&#39;il r&eacute;duit &agrave; tel point son usage que vous ne l&#39;auriez pas achet&eacute; ou l&#39;auriez pay&eacute; &agrave; un moindre prix. La garantie contre les vices cach&eacute;s ne s&#39;applique que lorsque le vice est ant&eacute;rieur &agrave; la vente. Vous disposez d&#39;un d&eacute;lai de 2 ans &agrave; compter de la d&eacute;couverte du vice pour agir.</p>\r\n\r\n<p>En pr&eacute;sence d&#39;un vice, nous nous engageons &agrave; remplacer votre produit ou &agrave; vous rembourser dans les plus brefs d&eacute;lais.</p>\r\n\r\n<p><em><strong>ARTICLE 10 : Conditions et d&eacute;lais de remboursement</strong></em></p>\r\n\r\n<p>Le remboursement des produits est int&eacute;gral. Il s&#39;effectue par virement bancaire / CB / ch&egrave;que, esp&egrave;ce dans les plus brefs d&eacute;lais et au plus tard dans les 30 jours &agrave; compter de la date d&#39;exercice du droit de r&eacute;tractation.</p>\r\n\r\n<p><em><strong>ARTICLE 11 : R&eacute;clamations du consommateur</strong></em></p>\r\n\r\n<p>Toute r&eacute;clamation du consommateur est &agrave; adresser par courrier postal &agrave; l&#39;adresse mentionn&eacute;e ci-contre&nbsp;: FlowerShop &ndash; Adresse &ndash; 75000 Paris ou par voie &eacute;lectronique &agrave; contact@email.fr.</p>\r\n\r\n<p><em><strong>ARTICLE 12 : Propri&eacute;t&eacute; intellectuelle</strong></em></p>\r\n\r\n<p>Tous les commentaires, images, illustrations de notre site nous sont exclusivement r&eacute;serv&eacute;s. Au titre de la propri&eacute;t&eacute; intellectuelle et du droit d&#39;auteur, toute utilisation est prohib&eacute;e sauf &agrave; usage priv&eacute;.</p>\r\n\r\n<p>Sans autorisation pr&eacute;alable, toute reproduction de notre site, qu&#39;elle soit partielle ou totale, est strictement interdite.</p>\r\n\r\n<p><em><strong>ARTICLE 13 : Responsabilit&eacute;</strong></em></p>\r\n\r\n<p>Conform&eacute;ment &agrave; l&#39;article 1147 du Code civil, nous engageons notre responsabilit&eacute; contractuelle de plein droit &agrave; votre &eacute;gard en cas d&#39;inex&eacute;cution ou de mauvaise ex&eacute;cution du contrat conclu.</p>\r\n\r\n<p>Toutefois, notre responsabilit&eacute; contractuelle ne peut &ecirc;tre engag&eacute;e dans les situations mentionn&eacute;es ci-dessous&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>cas de la force majeure.</li>\r\n	<li>fait &eacute;tranger qui ne peut nous &ecirc;tre imputable.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li>Les photographies, illustrations, images de notre site n&#39;ont aucune valeur contractuelle. Elles ne sauraient donc engager notre responsabilit&eacute;.</li>\r\n</ul>\r\n\r\n<p><em><strong>ARTICLE 14 : Donn&eacute;es &agrave; caract&egrave;re personnel</strong></em></p>\r\n\r\n<ul>\r\n	<li>Certaines informations relatives au client seront transmises au vendeur (&agrave; savoir le nom, pr&eacute;nom, adresse, code postal et num&eacute;ro de t&eacute;l&eacute;phone) et ce, afin de permettre le traitement et la livraison des produits command&eacute;s.</li>\r\n</ul>\r\n\r\n<p>Le site assure au client une collecte et un traitement d&#39;informations personnelles dans le respect de la vie priv&eacute;e conform&eacute;ment &agrave; la loi n&deg;78-17 du 6 janvier 1978 relative &agrave; l&#39;informatique, aux fichiers et aux libert&eacute;s.</p>\r\n\r\n<p>En vertu des articles 39 et 40 de la loi en date du 6 janvier 1978, le client dispose d&#39;un droit d&#39;acc&egrave;s, de rectification, de suppression et d&#39;opposition de ses donn&eacute;es personnelles. Le client exerce ce droit via&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>son espace personnel</li>\r\n	<li>un formulaire de contact</li>\r\n	<li>par mail &agrave; contact@email.fr</li>\r\n	<li>par voie postale au&nbsp;: FlowerShop - Adresse &ndash; 75000 Paris</li>\r\n</ul>\r\n\r\n<p><em><strong>ARTICLE 15 : Juridiction comp&eacute;tente et droit applicable</strong></em></p>\r\n\r\n<p>En cas de litige entre le client consommateur et notre soci&eacute;t&eacute;, le droit applicable est le droit fran&ccedil;ais.</p>\r\n\r\n<ul>\r\n	<li>Les juridictions fran&ccedil;aises ont seules comp&eacute;tences pour trancher le litige.</li>\r\n</ul>\r\n', NULL, 0, 1, 6, '2022-01-06', '2022-05-06'),
(2, 'Mentions légales', '<p><strong>Les informations l&eacute;gales de l&#39;entreprise :</strong></p>\r\n\r\n<ul>\r\n	<li>Nom : FlowerShop</li>\r\n	<li>G&eacute;rant : Nom Pr&eacute;nom</li>\r\n	<li>Adresse : adresse de l&#39;entreprise</li>\r\n	<li>Ville : 75000 Paris</li>\r\n	<li>Pays : France</li>\r\n	<li>T&eacute;l&eacute;phone : 01 02 03 04 05</li>\r\n	<li>N&deg; Siret : 00000000000000</li>\r\n	<li>RCS : Ville du RCS</li>\r\n</ul>\r\n\r\n<p>La version de ce site projet est h&eacute;berg&eacute; chez : adresse de votre h&eacute;bergeur</p>\r\n', NULL, 0, 1, 6, '2022-01-06', '2022-05-06'),
(3, 'Politique de confidentialité', '<p><strong>Donn&eacute;es personnelles</strong></p>\r\n\r\n<p>Notre site demande l&#39;enregistrement nominatif &agrave; ses visiteurs et proc&egrave;de &agrave; l&#39;enregistrement nominatif pour la cr&eacute;ation de l&#39;espace personnalis&eacute;.</p>\r\n\r\n<p>Lors de la cr&eacute;ation de votre compte, vous serez invit&eacute; &agrave; laisser des donn&eacute;es personnelles (noms, pr&eacute;noms, num&eacute;ros de t&eacute;l&eacute;phones, adresses postales, adresse &eacute;lectronique&hellip;). Le caract&egrave;re obligatoire ou facultatif des donn&eacute;es vous est signal&eacute; lors de la collecte par un ast&eacute;risque.</p>\r\n\r\n<p>Le cas &eacute;ch&eacute;ant, le formulaire de collecte pourra &ecirc;tre accompagn&eacute; d&#39;une case &agrave; cocher vous permettant d&#39;accepter ou de refuser que vos donn&eacute;es soient utilis&eacute;es &agrave; des fins commerciales pour le compte de tiers, et/ou c&eacute;d&eacute;es &agrave; des tiers.</p>\r\n\r\n<p>Nous ne collectons aucune donn&eacute;e sensible, &agrave; savoir aucune donn&eacute;e relative &agrave; vos origines raciales ou ethniques, &agrave; vos opinions politiques, philosophiques ou religieuses ou votre appartenance syndicale, ou qui sont relatives &agrave; votre sant&eacute; ou votre vie sexuelle.</p>\r\n\r\n<p>Par ailleurs, lors de la consultation de notre site web, nous sommes amen&eacute;s &agrave; collecter et traiter des donn&eacute;es relatives &agrave; votre navigation (notamment les cookies, votre adresse IP, les pages que vous avez consult&eacute;es et les recherches que vous avez effectu&eacute;es), et &agrave; votre terminal (type de navigateur utilis&eacute;, mod&egrave;le et version de votre syst&egrave;me d&rsquo;exploitation, r&eacute;solution de votre &eacute;cran, pr&eacute;sence de certains plug-ins, &hellip;). Nous r&eacute;aliserons &eacute;galement une g&eacute;olocalisation approximative de votre ville d&rsquo;origine. Ces donn&eacute;es seront utilis&eacute;es d&rsquo;une part pour r&eacute;aliser des statistiques d&rsquo;utilisation de notre site afin de le rendre plus pertinent, pour identifier les soci&eacute;t&eacute;s s&rsquo;int&eacute;ressant &agrave; nos produits., et pour vous proposer des offres publicitaires sur et en dehors de notre site en rapport avec vos centres d&rsquo;int&eacute;r&ecirc;t et, &eacute;ventuellement, de votre localit&eacute;.</p>\r\n', NULL, 0, 1, 6, '2022-01-06', '2022-05-06'),
(4, 'Information Cookies', '<p><strong>Cookies essentiels.</strong></p>\r\n\r\n<p>Ils sont strictement n&eacute;cessaires &agrave; l&rsquo;utilisation du service et permettent l&rsquo;utilisation des principales fonctionnalit&eacute;s du site, comme le cas &eacute;ch&eacute;ant l&rsquo;acc&egrave;s &agrave; votre compte personnel, ou encore de m&eacute;moriser les pr&eacute;f&eacute;rences d&#39;affichage de votre terminal (langue, param&egrave;tres d&#39;affichage) et d&#39;en tenir compte lors de vos visites, selon la charte graphique et les logiciels de visualisation ou de lecture de votre terminal. Ils peuvent inclure des cookies de r&eacute;seaux sociaux lorsque vous interagissez avec ces derniers. Ils nous permettent aussi de lier entre elles les diff&eacute;rentes pages consult&eacute;es pour vous assurer une navigation fluide.</p>\r\n\r\n<p>Pour param&eacute;trer :</p>\r\n\r\n<p>Vous pouvez d&eacute;sactiver compl&egrave;tement les cookies dans votre navigateur. Dans ce cas notre site ne fonctionnera plus normalement mais vous pourrez quand m&ecirc;me effectuer des actions basiques.</p>\r\n\r\n<p>1/ si vous utilisez le navigateur Internet Explorer</p>\r\n\r\n<ul>\r\n	<li>Dans Internet Explorer, cliquez sur le bouton &laquo; Outils &raquo;, puis sur &laquo; Options Internet &raquo;.</li>\r\n	<li>Sous l&rsquo;onglet Confidentialit&eacute; d&eacute;placez le curseur vers le haut pour bloquer tous les cookies ou vers le bas pour autoriser tous les cookies, puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>2/ si vous utilisez le navigateur Firefox</p>\r\n\r\n<ul>\r\n	<li>Allez dans le menu &laquo; Outils &raquo; du navigateur puis s&eacute;lectionnez le menu &laquo; Options &raquo;</li>\r\n	<li>Cliquez sur l&rsquo;onglet &laquo; vie priv&eacute;e &raquo;, d&eacute;cochez la case &laquo; Accepter les cookies &raquo; puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3/ si vous utilisez le navigateur Safari</p>\r\n\r\n<ul>\r\n	<li>Dans votre navigateur, choisissez le menu &laquo; &Eacute;dition &raquo; puis s&eacute;lectionnez &laquo; Pr&eacute;f&eacute;rences &raquo;.</li>\r\n	<li>Cliquez sur &laquo; Confidentialit&eacute; &raquo;.</li>\r\n	<li>Positionnez le r&eacute;glage &laquo; Bloquer les cookies &raquo; sur &laquo; Toujours &raquo; puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>4/ si vous utilisez le navigateur Google Chrome</p>\r\n\r\n<ul>\r\n	<li>Cliquez sur le menu Chrome dans la barre d&#39;outils du navigateur.</li>\r\n	<li>S&eacute;lectionnez &laquo; Param&egrave;tres &raquo;.</li>\r\n	<li>Cliquez sur &laquo; Afficher les param&egrave;tres avanc&eacute;s &raquo;.</li>\r\n	<li>Dans la section &laquo; Confidentialit&eacute; &raquo;, cliquez sur le bouton &laquo; Param&egrave;tres de contenu &raquo;.</li>\r\n	<li>Dans la section &laquo; Cookies &raquo;, s&eacute;lectionnez &laquo; Interdire &agrave; tous les sites de stocker des donn&eacute;es &raquo; et cochez la case &laquo; Bloquer les cookies et les donn&eacute;es de site tiers &raquo;, puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Cookies fonctionnels de mesure d&rsquo;audience et de statistiques.</strong></p>\r\n\r\n<p>Ils nous permettent de faire vivre le site et d&rsquo;&eacute;tablir des statistiques et comptages de fr&eacute;quentation et d&#39;utilisation de ses rubriques et contenus, pour r&eacute;aliser des &eacute;tudes afin d&rsquo;am&eacute;liorer le contenu (mesure du nombre de visites, de pages vues ou encore de l&#39;activit&eacute; des visiteurs sur le site et de leur fr&eacute;quence de retour). Ils nous permettent &eacute;galement d&rsquo;analyser la navigation des internautes afin d&rsquo;am&eacute;liorer notre service ou de d&eacute;tecter des dysfonctionnements.</p>\r\n\r\n<p>Cookies de commercialisation.</p>\r\n\r\n<p>Ils sont susceptibles d&rsquo;&ecirc;tre plac&eacute;s dans votre terminal, afin d&rsquo;identifier vos centres d&rsquo;int&eacute;r&ecirc;t au travers des produits consult&eacute;s sur notre site et de collecter des donn&eacute;es de navigation afin de personnaliser l&rsquo;offre publicitaire qui vous est adress&eacute;e sur et en dehors de nos sites.</p>\r\n\r\n<p>Vous pouvez vous opposer &agrave; l&rsquo;usage de vos donn&eacute;es de navigation &agrave; des fins publicitaires par nos partenaires</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Vous pouvez &eacute;galement vous opposer &agrave; tous les cookies tiers depuis votre navigateur :</p>\r\n\r\n<p>1/ si vous utilisez le navigateur Internet Explorer</p>\r\n\r\n<ul>\r\n	<li>Dans Internet Explorer, cliquez sur le bouton &laquo; Outils &raquo;, puis sur &laquo; Options Internet &raquo;.</li>\r\n	<li>Sous l&rsquo;onglet Confidentialit&eacute;, sous Cookies, activez l&rsquo;option Bloquer tous les cookies tiers, puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>2/ si vous utilisez le navigateur Firefox</p>\r\n\r\n<ul>\r\n	<li>Allez dans le menu &laquo; Outils &raquo; du navigateur puis s&eacute;lectionnez &laquo; Options &raquo;</li>\r\n	<li>Cliquez sur l&rsquo;onglet &laquo; vie priv&eacute;e &raquo;, passez le param&egrave;tre &laquo; Accepter les cookies tiers &raquo; &agrave; &laquo; jamais &raquo;, puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3/ si vous utilisez le navigateur Safari</p>\r\n\r\n<ul>\r\n	<li>Dans votre navigateur, choisissez le menu &laquo; &Eacute;dition &raquo; puis s&eacute;lectionnez &laquo; Pr&eacute;f&eacute;rences &raquo;.</li>\r\n	<li>Cliquez sur &laquo; Confidentialit&eacute; &raquo;.</li>\r\n	<li>Positionnez le r&eacute;glage &laquo; Bloquer les cookies sur &laquo; Des tierces parties et des annonceurs &raquo; puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>4/ si vous utilisez le navigateur Google Chrome</p>\r\n\r\n<ul>\r\n	<li>Cliquez sur le menu Chrome dans la barre d&#39;outils du navigateur.</li>\r\n	<li>S&eacute;lectionnez &laquo; Param&egrave;tres &raquo;.</li>\r\n	<li>Cliquez sur &laquo; Afficher les param&egrave;tres avanc&eacute;s &raquo;.</li>\r\n	<li>Dans la section &laquo; Confidentialit&eacute; &raquo;, cliquez sur le bouton &laquo; Param&egrave;tres de contenu &raquo;.</li>\r\n	<li>Dans la section &laquo; Cookies &raquo;, cochez la case &laquo; Bloquer les cookies et les donn&eacute;es de site tiers &raquo;, puis cliquez sur OK.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>La CNIL propose par ailleurs sur son site &agrave; l&rsquo;adresse&nbsp;<a href=\"https://www.cnil.fr/vos-droits/vos-traces/les-cookies/conseils-aux-internautes\" target=\"_blank\">https://www.cnil.fr/vos-droits/vos-traces/les-cookies/conseils-aux-internautes</a>&nbsp;un large panel d&rsquo;outils permettant de limiter la tra&ccedil;abilit&eacute; de votre navigation sur Internet : extensions pour votre navigateur permettant de bloquer les traceurs, les boutons de partage sur les r&eacute;seaux sociaux, le chargement de ressources en provenance d&rsquo;autres sites&hellip;</p>\r\n', NULL, 0, 1, 6, '2022-01-06', '2022-05-06'),
(5, 'Fleuriste FlowerShop', '<p>Votre espace floral dispose de plantes vertes et plantes fleuries d&#39;int&eacute;rieur et d&#39;ext&eacute;rieur mais aussi de fleurs coup&eacute;es r&eacute;gionales et exotiques. FlowerShop vous propose des compositions florales pour tous vos &eacute;v&eacute;nements comme une naissance, un bapt&ecirc;me, un mariage, un anniversaire ou un deuil. Nous livrons dans un rayon de 20 km autour de Paris vos compositions florales, bouquets et plantes. Votre boutique dispose de bouquets et r&eacute;alise le fleurissement de concession fun&eacute;raire.</p>\r\n', NULL, 0, 1, 1, '2022-01-10', '2022-05-06'),
(6, 'Nos services', '<p>Voici les services que nous proposons pour la satisfaction de nos clients.</p>\r\n', NULL, 0, 0, 1, '2022-02-27', '2022-02-27'),
(7, 'A propos de nous...', '<p>Les informations sur votre entreprise (cr&eacute;ation, photo, projet, d&eacute;m&eacute;nagement, agrandissement...)</p>\r\n', NULL, 1, 1, 6, '2022-01-07', '2022-05-06');

-- --------------------------------------------------------

--
-- Structure de la table `content_location`
--

CREATE TABLE `content_location` (
  `id` int(11) NOT NULL,
  `id_location` int(10) DEFAULT NULL,
  `name_location` varchar(255) DEFAULT NULL,
  `authorized_use` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `content_location`
--

INSERT INTO `content_location` (`id`, `id_location`, `name_location`, `authorized_use`) VALUES
(1, 1, 'Bloc Accueil de base', 0),
(2, 2, 'Bloc Adresse pied de page', 1),
(3, 3, 'Bloc Liens utiles pied de page', 1),
(4, 4, 'Bloc Mon compte pied de page', 1),
(5, 5, 'Bloc Suivez-nous pied de page', 1),
(6, 6, 'Bloc Société pied de page', 1);

-- --------------------------------------------------------

--
-- Structure de la table `employee`
--

CREATE TABLE `employee` (
  `id_admin` int(10) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `id_employee` int(10) DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_last_connection` datetime DEFAULT NULL,
  `pseudo` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `flower_workshop`
--

CREATE TABLE `flower_workshop` (
  `id_work` int(10) NOT NULL,
  `id_day` varchar(10) DEFAULT NULL,
  `work_date` date NOT NULL,
  `time_start` text,
  `time_end` int(11) DEFAULT NULL,
  `max_space` int(10) DEFAULT NULL,
  `space_available` int(10) DEFAULT NULL,
  `price_tax` decimal(10,2) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `cancel` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `flower_workshop`
--

INSERT INTO `flower_workshop` (`id_work`, `id_day`, `work_date`, `time_start`, `time_end`, `max_space`, `space_available`, `price_tax`, `active`, `cancel`) VALUES
(1, '6', '2022-05-28', '14', 16, 10, 9, '60.00', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `flower_workshop_user`
--

CREATE TABLE `flower_workshop_user` (
  `id_work` int(10) DEFAULT NULL,
  `id_user` int(10) NOT NULL DEFAULT '0',
  `confirmed` int(10) NOT NULL DEFAULT '0',
  `canceled` int(10) NOT NULL DEFAULT '0',
  `date_upd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `flower_workshop_user`
--

INSERT INTO `flower_workshop_user` (`id_work`, `id_user`, `confirmed`, `canceled`, `date_upd`) VALUES
(1, 1, 1, 0, '2022-05-06');

-- --------------------------------------------------------

--
-- Structure de la table `gender`
--

CREATE TABLE `gender` (
  `id_gender` int(10) NOT NULL,
  `gender_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `gender`
--

INSERT INTO `gender` (`id_gender`, `gender_name`) VALUES
(1, 'M'),
(2, 'Mme');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(10) NOT NULL,
  `id_product` int(10) DEFAULT NULL,
  `id_category` int(10) DEFAULT NULL,
  `name_large` varchar(255) DEFAULT NULL,
  `description_image` varchar(255) DEFAULT NULL,
  `name_category` varchar(255) DEFAULT NULL,
  `name_miniature` varchar(255) DEFAULT NULL,
  `orientation` varchar(255) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_upd` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `id_product`, `id_category`, `name_large`, `description_image`, `name_category`, `name_miniature`, `orientation`, `date_add`, `date_upd`) VALUES
(13, 0, 1000, '2085761824-28c9b4ebf13c.jpg', 'Naissance', '2085761824-28c9b4ebf13c_min.jpg', NULL, 'portrait', '2022-05-06 15:59:29', NULL),
(14, 0, 1000, '97827832-naissance1.jpg', 'Naissance', '97827832-naissance1_min.jpg', NULL, 'landscape', '2022-05-06 15:59:39', NULL),
(15, 0, 1000, '1270625321-shutterstock_1029085411.jpg', 'Naissance', '1270625321-shutterstock_1029085411_min.jpg', NULL, 'landscape', '2022-05-06 15:59:47', NULL),
(16, 0, 2000, '1162582069-bouquet-mariage-mains-femme.jpg', 'Mariage', '1162582069-bouquet-mariage-mains-femme_min.jpg', NULL, 'landscape', '2022-05-06 16:08:55', NULL),
(17, 0, 2000, '1522112986-pexels-irina-iriser-1393478.jpg', 'Mariage', '1522112986-pexels-irina-iriser-1393478_min.jpg', NULL, 'portrait', '2022-05-06 16:10:59', NULL),
(18, 0, 2000, '2091172056-carrementfleurs_choix_fleurs_reception_mariage.jpg', 'Mariage', '2091172056-carrementfleurs_choix_fleurs_reception_mariage_min.jpg', NULL, 'landscape', '2022-05-06 16:11:16', NULL),
(19, 0, 2000, '1506202047-pexels-irina-iriser-916361.jpg', 'Mariage', '1506202047-pexels-irina-iriser-916361_min.jpg', NULL, 'portrait', '2022-05-06 16:11:35', NULL),
(20, 0, 2000, '1838219328-fleurs-mariage-table.jpg', '', '1838219328-fleurs-mariage-table_min.jpg', NULL, 'landscape', '2022-05-06 16:12:03', '2022-05-06 16:14:16'),
(22, 0, 2000, '777577797-pexels-brent-keane-1702373.jpg', '', '777577797-pexels-brent-keane-1702373_min.jpg', NULL, 'landscape', '2022-05-06 16:12:22', '2022-05-06 16:12:52'),
(23, 0, 2000, '1281871483-pexels-moose-photos-1587042.jpg', 'Mariage', '1281871483-pexels-moose-photos-1587042_min.jpg', NULL, 'portrait', '2022-05-06 16:15:41', NULL),
(24, 0, 2000, '8353476-pexels-rocsana-nicoleta-gurza-948185.jpg', 'Mariage', '8353476-pexels-rocsana-nicoleta-gurza-948185_min.jpg', NULL, 'portrait', '2022-05-06 16:16:14', NULL),
(25, 0, 2000, '22827627-pexels-valeria-boltneva-585410.jpg', 'Mariage', '22827627-pexels-valeria-boltneva-585410_min.jpg', NULL, 'portrait', '2022-05-06 16:16:30', NULL),
(26, 0, 2000, '1781093942-pexels-wendel-moretti-1730877.jpg', 'Mariage', '1781093942-pexels-wendel-moretti-1730877_min.jpg', NULL, 'portrait', '2022-05-06 16:16:40', NULL),
(27, 0, 2000, '2037020643-pexels-oleksandr-pidvalnyi-342257.jpg', 'Mariage', '2037020643-pexels-oleksandr-pidvalnyi-342257_min.jpg', NULL, 'landscape', '2022-05-06 16:17:44', NULL),
(28, 0, 2000, '1019163483-pexels-rovenimagescom-949582.jpg', 'Mariage', '1019163483-pexels-rovenimagescom-949582_min.jpg', NULL, 'landscape', '2022-05-06 16:18:10', NULL),
(29, 0, 2000, '76597369-pexels-terje-sollie-313697.jpg', 'Mariage', '76597369-pexels-terje-sollie-313697_min.jpg', NULL, 'landscape', '2022-05-06 16:18:26', NULL),
(30, 0, 2000, '2051946342-pexels-trung-nguyen-2959192.jpg', 'Mariage', '2051946342-pexels-trung-nguyen-2959192_min.jpg', NULL, 'landscape', '2022-05-06 16:19:56', NULL),
(31, 0, 2000, '2128262589-shutterstock_1029085411.jpg', 'Mariage', '2128262589-shutterstock_1029085411_min.jpg', NULL, 'landscape', '2022-05-06 16:20:22', NULL),
(32, 0, 3000, '963558032-beneficiez-des-fleurs-low-cost-pour-un-enterrement-.jpg', 'Deuil', '963558032-beneficiez-des-fleurs-low-cost-pour-un-enterrement-_min.jpg', NULL, 'landscape', '2022-05-06 16:29:28', NULL),
(33, 0, 3000, '907142591-fleurs-décès.jpg', 'Deuil', '907142591-fleurs-décès_min.jpg', NULL, 'landscape', '2022-05-06 16:29:39', NULL),
(35, 0, 3000, '1049509810-deuil-carre-roses-bordeaux-10.jpg', 'Deuil', '1049509810-deuil-carre-roses-bordeaux-10_min.jpg', NULL, 'portrait', '2022-05-06 16:30:07', NULL),
(36, 0, 3000, '1524735942-les-fleurs-qui-rendraient-le-meilleur-hommage-a-votre-defunt.jpg', 'Deuil', '1524735942-les-fleurs-qui-rendraient-le-meilleur-hommage-a-votre-defunt_min.jpg', NULL, 'landscape', '2022-05-06 16:30:23', NULL),
(37, 0, 3000, '195318609-a9110dfa798a.jpg', 'Deuil', '195318609-a9110dfa798a_min.jpg', NULL, 'landscape', '2022-05-06 16:30:42', NULL),
(38, 0, 3000, '1825837305-deuil-prestations-05.jpg', 'Deuil', '1825837305-deuil-prestations-05_min.jpg', NULL, 'landscape', '2022-05-06 16:30:51', NULL),
(40, 0, 3000, '1538006435-croix-fleurs-deuil-rouge.jpg', 'Deuil', '1538006435-croix-fleurs-deuil-rouge_min.jpg', NULL, 'portrait', '2022-05-06 16:34:10', NULL),
(41, 0, 3000, '1412884836-les jasmin artisan fleuriste deuil 33.jpg', 'Deuil', '1412884836-les jasmin artisan fleuriste deuil 33_min.jpg', NULL, 'landscape', '2022-05-06 16:34:24', NULL),
(42, 0, 4000, '1580849827-moderne-vertical.jpg', 'Composition', '1580849827-moderne-vertical_min.jpg', NULL, 'portrait', '2022-05-06 16:37:02', NULL),
(43, 0, 4000, '897140627-28841448.jpg', 'Composition', '897140627-28841448_min.jpg', NULL, 'portrait', '2022-05-06 16:42:11', NULL),
(44, 0, 4000, '217724725-nos-evenements-mariage570f67e0e6b53.jpg', 'Composition', '217724725-nos-evenements-mariage570f67e0e6b53_min.jpg', NULL, 'portrait', '2022-05-06 16:42:25', NULL),
(45, 0, 4000, '351059152-img_0791-848x480.jpg', 'Composition', '351059152-img_0791-848x480_min.jpg', NULL, 'landscape', '2022-05-06 16:42:39', NULL),
(46, 0, 4000, '1531567877-thumbnail.jpg', 'Composition', '1531567877-thumbnail_min.jpg', NULL, 'portrait', '2022-05-06 16:43:00', NULL),
(47, 0, 4000, '388804010-e5f7a81baab540b8c5186bb09a015bd5.jpg', 'Composition', '388804010-e5f7a81baab540b8c5186bb09a015bd5_min.jpg', NULL, 'landscape', '2022-05-06 16:43:14', NULL),
(48, 0, 4000, '2120848476-lanterne-noire-avec-composition-florale.jpg', 'Composition', '2120848476-lanterne-noire-avec-composition-florale_min.jpg', NULL, 'portrait', '2022-05-06 16:43:27', NULL),
(49, 1, 5000, '1619551076_coffret_bougie_max.jpg', 'Coffret bougie', '1619551076_coffret_bougie.jpg', '1619551076_coffret_bougie_min.jpg', 'portrait', '2022-05-06 16:49:10', '2022-05-06 16:49:10'),
(50, 2, 5000, '492705950_coffret_de_3_bougies_parfumees_max.jpg', 'Coffret 3 bougies parfumées', '492705950_coffret_de_3_bougies_parfumees.jpg', '492705950_coffret_de_3_bougies_parfumees_min.jpg', 'landscape', '2022-05-06 16:54:39', '2022-05-06 16:54:39'),
(51, 3, 6000, '656226661_decoration_mural_3_pieces_max.jpg', 'Décoration murale 3 motifs', '656226661_decoration_mural_3_pieces.jpg', '656226661_decoration_mural_3_pieces_min.jpg', 'portrait', '2022-05-06 17:02:24', '2022-05-06 17:02:24'),
(52, 4, 5000, '1479949291_decoration_murale_en_metal_dore_max.jpg', 'Décoration feuille en métal doré', '1479949291_decoration_murale_en_metal_dore.jpg', '1479949291_decoration_murale_en_metal_dore_min.jpg', 'portrait', '2022-05-06 17:05:53', '2022-05-06 17:05:53');

-- --------------------------------------------------------

--
-- Structure de la table `messaging`
--

CREATE TABLE `messaging` (
  `id_message` int(10) NOT NULL,
  `id_thread_user` int(10) DEFAULT NULL,
  `id_employee` int(10) NOT NULL DEFAULT '0',
  `msg_notification` int(10) NOT NULL DEFAULT '1',
  `shop_message` longtext,
  `date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messaging`
--

INSERT INTO `messaging` (`id_message`, `id_thread_user`, `id_employee`, `msg_notification`, `shop_message`, `date_add`) VALUES
(1, 1, 0, 0, 'Bonjour,\r\n\r\nfaites-vous les livraison sur Paris ?\r\n\r\nCordialement,\r\n\r\nJohn Doo', '2022-05-06 10:59:45'),
(2, 1, 1, 1, 'Bonjour John,\r\n\r\nnous pouvons vous faire livrer sur Paris bien sûr.\r\n\r\nCordialement,\r\n\r\nL\'équipe de FlowerShop', '2022-05-06 11:09:06');

-- --------------------------------------------------------

--
-- Structure de la table `messaging_thread`
--

CREATE TABLE `messaging_thread` (
  `id_thread_user` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `email` varchar(60) NOT NULL,
  `date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messaging_thread`
--

INSERT INTO `messaging_thread` (`id_thread_user`, `id_user`, `email`, `date_add`) VALUES
(1, 1, 'john.doo@gmail.com', '2022-05-06 10:59:45');

-- --------------------------------------------------------

--
-- Structure de la table `method_delivery`
--

CREATE TABLE `method_delivery` (
  `id_delivery_method` int(10) NOT NULL,
  `name_delivery` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `method_delivery`
--

INSERT INTO `method_delivery` (`id_delivery_method`, `name_delivery`) VALUES
(1, 'Retrait sur place');

-- --------------------------------------------------------

--
-- Structure de la table `method_payment`
--

CREATE TABLE `method_payment` (
  `id_payment_method` int(10) NOT NULL,
  `name_payment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `method_payment`
--

INSERT INTO `method_payment` (`id_payment_method`, `name_payment`) VALUES
(1, 'Magasin');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `id_address_billing` int(10) DEFAULT NULL,
  `delivery_method` int(10) DEFAULT NULL,
  `payment_method` int(10) DEFAULT NULL,
  `order_status` int(10) DEFAULT NULL,
  `current_status` int(10) DEFAULT '1',
  `order_notification` int(10) NOT NULL DEFAULT '1',
  `date_add` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total_amount`, `id_address_billing`, `delivery_method`, `payment_method`, `order_status`, `current_status`, `order_notification`, `date_add`) VALUES
(1, 1, '45.80', 1, 1, 1, 1, 1, 1, '2022-05-06 17:08:01');

-- --------------------------------------------------------

--
-- Structure de la table `order_address`
--

CREATE TABLE `order_address` (
  `id_order` int(10) NOT NULL,
  `id_address` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `addresse` varchar(255) DEFAULT NULL,
  `address_comp` varchar(255) DEFAULT NULL,
  `zip_code` varchar(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `order_address`
--

INSERT INTO `order_address` (`id_order`, `id_address`, `id_user`, `alias`, `lastname`, `firstname`, `company`, `addresse`, `address_comp`, `zip_code`, `city`, `country`, `phone`) VALUES
(1, 1, 1, 'Perso', 'Doo', 'John', '-', 'Rue de la liberté', NULL, '75000', 'Paris', 'France', '0102030405');

-- --------------------------------------------------------

--
-- Structure de la table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_order` int(10) NOT NULL,
  `id_product` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `unit_price_product` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `order_detail`
--

INSERT INTO `order_detail` (`id_order`, `id_product`, `quantity`, `unit_price_product`) VALUES
(1, 1, 1, '15.90'),
(1, 3, 1, '29.90');

-- --------------------------------------------------------

--
-- Structure de la table `order_detail_history`
--

CREATE TABLE `order_detail_history` (
  `id` int(11) NOT NULL,
  `id_order` int(10) DEFAULT NULL,
  `history_id_product` int(10) DEFAULT NULL,
  `history_name_product` varchar(2555) DEFAULT NULL,
  `history_reference_product` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `order_detail_history`
--

INSERT INTO `order_detail_history` (`id`, `id_order`, `history_id_product`, `history_name_product`, `history_reference_product`) VALUES
(1, 1, 1, 'Bougie Mamie en Or', 'bgie-mami-or'),
(2, 1, 3, 'Décoration mural 3 pièces', 'deco-mur-3pc');

-- --------------------------------------------------------

--
-- Structure de la table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `id_order` int(10) NOT NULL,
  `id_status_order` int(10) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `order_history`
--

INSERT INTO `order_history` (`id`, `id_order`, `id_status_order`, `date_add`) VALUES
(1, 1, 1, '2022-05-06 17:08:01');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id_product` int(10) NOT NULL,
  `category_product` int(10) DEFAULT NULL,
  `name_product` varchar(255) DEFAULT NULL,
  `reference_product` varchar(255) DEFAULT NULL,
  `price_tax_product` decimal(10,2) DEFAULT NULL,
  `short_description_product` mediumtext,
  `description_product` longtext,
  `active` tinyint(4) NOT NULL,
  `date_add` date DEFAULT NULL,
  `date_upd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id_product`, `category_product`, `name_product`, `reference_product`, `price_tax_product`, `short_description_product`, `description_product`, `active`, `date_add`, `date_upd`) VALUES
(1, 5000, 'Bougie Mamie en Or', 'bgie-mami-or', '15.90', '<p>Bougie Mamie en Or &agrave; offrir en cadeau.</p>\r\n', '<p>Bougie parfum&eacute;e &agrave; la rose.</p>\r\n', 1, '2022-05-06', '2022-05-06'),
(2, 5000, 'Coffret de 3 bougies parfumées', 'cof-3bgie-parf', '29.90', '<p>Coffret de 3 bougies parfum&eacute;es</p>\r\n', '<p>3 parfums :</p>\r\n\r\n<p>- lavande</p>\r\n\r\n<p>- citron</p>\r\n\r\n<p>- rose</p>\r\n', 1, '2022-05-06', NULL),
(3, 6000, 'Décoration mural 3 pièces', 'deco-mur-3pc', '29.90', '<p>D&eacute;coration murale en m&eacute;tal - 3 pi&egrave;ces</p>\r\n', '<p>3 motifs diff&eacute;rents.</p>\r\n', 1, '2022-05-06', NULL),
(4, 6000, 'Décoration murale en métal doré', 'deco-feuille', '16.90', '<p>D&eacute;coration murale - Mod&egrave;le feuille</p>\r\n', '<p>- Coloris : dor&eacute;</p>\r\n\r\n<p>- Dimensions : 53x56 cm</p>\r\n', 1, '2022-05-06', '2022-05-06');

-- --------------------------------------------------------

--
-- Structure de la table `shop`
--

CREATE TABLE `shop` (
  `id` int(10) NOT NULL,
  `id_gender` int(10) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `addresse` varchar(255) DEFAULT NULL,
  `zip_code` varchar(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(14) DEFAULT NULL,
  `siret` varchar(14) DEFAULT NULL,
  `rcs` varchar(255) DEFAULT NULL,
  `localization_map` longtext,
  `homepage_teaser_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop`
--

INSERT INTO `shop` (`id`, `id_gender`, `lastname`, `firstname`, `capacity`, `company`, `addresse`, `zip_code`, `city`, `country`, `phone`, `siret`, `rcs`, `localization_map`, `homepage_teaser_title`) VALUES
(1, 1, 'Nom', 'Prénom', 'Fonction', 'FlowerShop', 'Adresse', '75000', 'Paris', 'France', '01.02.03.04.05', '00000000000000', 'Ville du RCS', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83998.76457543198!2d2.276995541486795!3d48.858946580710985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis!5e0!3m2!1sfr!2sfr!4v1651829026412!5m2!1sfr!2sfr                                                                                                                                                                                                             ', 'Toute l\'équipe de FlowerShop vous accueil');

-- --------------------------------------------------------

--
-- Structure de la table `shop_days`
--

CREATE TABLE `shop_days` (
  `id_day` int(11) DEFAULT NULL,
  `name_shop_day` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop_days`
--

INSERT INTO `shop_days` (`id_day`, `name_shop_day`) VALUES
(0, 'Dimanche'),
(1, 'Lundi'),
(2, 'Mardi'),
(3, 'Mercredi'),
(4, 'Jeudi'),
(5, 'Vendredi'),
(6, 'Samedi');

-- --------------------------------------------------------

--
-- Structure de la table `shop_event`
--

CREATE TABLE `shop_event` (
  `id_event` int(11) NOT NULL,
  `title_shop_event` varchar(255) DEFAULT NULL,
  `description_shop_event` text,
  `event_start` date DEFAULT NULL,
  `event_end` date DEFAULT NULL,
  `img_event_large` varchar(255) DEFAULT NULL,
  `img_event_medium` varchar(255) DEFAULT NULL,
  `img_event_miniature` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `date_add` date DEFAULT NULL,
  `date_upd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop_event`
--

INSERT INTO `shop_event` (`id_event`, `title_shop_event`, `description_shop_event`, `event_start`, `event_end`, `img_event_large`, `img_event_medium`, `img_event_miniature`, `active`, `date_add`, `date_upd`) VALUES
(6, 'Pour la fête des Mères', '<h1><strong>-10%* sur toutes les compositions !</strong></h1>\r\n\r\n<p>* Une offre &agrave; ne pas manquer pour faire plaisir ce jour l&agrave;.</p>\r\n', '2022-05-15', '2022-05-29', '1605366560_pour_la_fete_des_meres_max.jpg', '1605366560_pour_la_fete_des_meres_med.jpg', '1605366560_pour_la_fete_des_meres_min.jpg', 1, '2022-02-23', '2022-05-06');

-- --------------------------------------------------------

--
-- Structure de la table `shop_service`
--

CREATE TABLE `shop_service` (
  `id_service` int(10) NOT NULL,
  `title_service` varchar(255) NOT NULL,
  `description_service` text NOT NULL,
  `active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `shop_service`
--

INSERT INTO `shop_service` (`id_service`, `title_service`, `description_service`, `active`) VALUES
(1, 'Livraison à domicile', 'Notre service de livraison à domicile vous permet de faire livrer vos fleurs dans un rayon de 30 Kms autour de notre boutique.                                                                                            ', 1),
(2, 'Vente à distance', 'Nous vous offrons la possibilité de passer vos commandes à distance par téléphone ou depuis notre site internet. Pour le moment, seul le click & collect est fonctionnel.', 1),
(3, 'Carte de fidélité', 'Avec la carte de fidélité Bouton d\'Or, vous gagnez des points sur vos achats.', 1),
(4, 'Conseils choix', 'Notre équipe vous conseillera pour le choix de vos bouquets, compositions ou décoration. ', 1),
(5, 'Conseils entretien', 'Notre équipe saura vous conseiller pour garder vos bouquets ou compositions le plus longtemps possible.', 1),
(6, 'Click & Collect', 'Ce service vous permet de récupérer vos commandes passés par internet ou par téléphone.', 1);

-- --------------------------------------------------------

--
-- Structure de la table `social_network`
--

CREATE TABLE `social_network` (
  `id` int(10) NOT NULL,
  `network_name` varchar(255) DEFAULT NULL,
  `network_url` varchar(255) DEFAULT NULL,
  `network_img` varchar(255) DEFAULT NULL,
  `network_description` varchar(45) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `social_network`
--

INSERT INTO `social_network` (`id`, `network_name`, `network_url`, `network_img`, `network_description`, `active`) VALUES
(1, 'Facebook', 'https://www.facebook.com', 'facebook_brands.svg', 'La page Facebook de FlowerShop', 1),
(2, 'Twitter', 'https://www.twitter.com', 'twitter_brands.svg', 'Logo Twitter', 0),
(3, 'Instagram', 'https://www.instagram.com', 'instagram_brands.svg', 'Logo Instagram', 0),
(4, 'Pinterest', 'https://www.pinterest.com', 'pinterest_brands.svg', 'Logo Pinterest', 0),
(5, 'Linkedin', 'https://www.linkedin.com', 'linkedin_brands.svg', 'Logo Linkedin', 0),
(6, 'WhatsApp', 'https://api.whatsapp.com/send?phone=0102030405', 'whatsapp_brands.svg', 'Contactez nous sur WhatsApp', 1);

-- --------------------------------------------------------

--
-- Structure de la table `status_order`
--

CREATE TABLE `status_order` (
  `id_status` int(10) NOT NULL,
  `name_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `status_order`
--

INSERT INTO `status_order` (`id_status`, `name_status`) VALUES
(1, 'Attente validation'),
(2, 'Validée'),
(3, 'Prête'),
(4, 'Terminée'),
(5, 'Annulée');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `id_gender` int(10) NOT NULL DEFAULT '3',
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gdpr_consent` int(10) DEFAULT NULL,
  `user_notification` int(10) NOT NULL DEFAULT '1',
  `date_add` datetime DEFAULT NULL,
  `date_upd` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `id_gender`, `lastname`, `firstname`, `email`, `passwd`, `birthday`, `gdpr_consent`, `user_notification`, `date_add`, `date_upd`) VALUES
(1, 1, 'Doo', 'John', 'john.doo@gmail.com', '$2y$10$KjMtwruTF2GG2esONscojOhJ3nCzUhPmram9ZBTKrcRPhKVmoLxUi', '1975-05-06', 1, 1, '2022-05-06 10:57:22', '2022-05-06 10:57:22');

-- --------------------------------------------------------

--
-- Structure de la table `work_hour`
--

CREATE TABLE `work_hour` (
  `id` int(10) NOT NULL,
  `start_day` int(10) DEFAULT NULL,
  `day` varchar(255) NOT NULL,
  `hour_morning_start` time DEFAULT NULL,
  `hour_morning_end` time DEFAULT NULL,
  `hour_afternoon_start` time DEFAULT NULL,
  `hour_afternoon_end` time DEFAULT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `work_hour`
--

INSERT INTO `work_hour` (`id`, `start_day`, `day`, `hour_morning_start`, `hour_morning_end`, `hour_afternoon_start`, `hour_afternoon_end`, `active`) VALUES
(1, 0, 'Lundi', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0),
(2, 1, 'Mardi', '09:00:00', '12:30:00', '14:30:00', '19:00:00', 1),
(3, 0, 'Mercredi', '09:00:00', '12:30:00', '14:30:00', '19:00:00', 1),
(4, 0, 'Jeudi', '09:00:00', '12:30:00', '14:30:00', '19:00:00', 1),
(5, 0, 'Vendredi', '09:00:00', '12:30:00', '14:30:00', '19:00:00', 1),
(6, 0, 'Samedi', '09:00:00', '12:30:00', '14:30:00', '19:00:00', 1),
(7, 0, 'Dimanche', '09:00:00', '13:00:00', '00:00:00', '00:00:00', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `addresse`
--
ALTER TABLE `addresse`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `idUser` (`id_user`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id_page`);

--
-- Index pour la table `content_location`
--
ALTER TABLE `content_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_location` (`id_location`);

--
-- Index pour la table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_employee` (`id_employee`);

--
-- Index pour la table `flower_workshop`
--
ALTER TABLE `flower_workshop`
  ADD PRIMARY KEY (`id_work`);

--
-- Index pour la table `flower_workshop_user`
--
ALTER TABLE `flower_workshop_user`
  ADD KEY `id_work` (`id_work`);

--
-- Index pour la table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id_gender`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`id_category`),
  ADD KEY `id_product` (`id_product`);

--
-- Index pour la table `messaging`
--
ALTER TABLE `messaging`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `thread` (`id_thread_user`);

--
-- Index pour la table `messaging_thread`
--
ALTER TABLE `messaging_thread`
  ADD PRIMARY KEY (`id_thread_user`);

--
-- Index pour la table `method_delivery`
--
ALTER TABLE `method_delivery`
  ADD PRIMARY KEY (`id_delivery_method`);

--
-- Index pour la table `method_payment`
--
ALTER TABLE `method_payment`
  ADD PRIMARY KEY (`id_payment_method`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_address_billing` (`id_address_billing`),
  ADD KEY `delivery_method` (`delivery_method`),
  ADD KEY `payment_method` (`payment_method`),
  ADD KEY `order_status` (`order_status`),
  ADD KEY `current_status` (`current_status`);

--
-- Index pour la table `order_address`
--
ALTER TABLE `order_address`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_address` (`id_address`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `order_detail`
--
ALTER TABLE `order_detail`
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_product` (`id_product`);

--
-- Index pour la table `order_detail_history`
--
ALTER TABLE `order_detail_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`);

--
-- Index pour la table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_status_order` (`id_status_order`),
  ADD KEY `id_order_2` (`id_order`) USING BTREE;

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_category` (`category_product`);

--
-- Index pour la table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gender` (`id_gender`);

--
-- Index pour la table `shop_days`
--
ALTER TABLE `shop_days`
  ADD KEY `id_day` (`id_day`);

--
-- Index pour la table `shop_event`
--
ALTER TABLE `shop_event`
  ADD PRIMARY KEY (`id_event`);

--
-- Index pour la table `shop_service`
--
ALTER TABLE `shop_service`
  ADD PRIMARY KEY (`id_service`);

--
-- Index pour la table `social_network`
--
ALTER TABLE `social_network`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `status_order`
--
ALTER TABLE `status_order`
  ADD PRIMARY KEY (`id_status`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idGender` (`id_gender`);

--
-- Index pour la table `work_hour`
--
ALTER TABLE `work_hour`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `addresse`
--
ALTER TABLE `addresse`
  MODIFY `id_address` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `content`
--
ALTER TABLE `content`
  MODIFY `id_page` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `content_location`
--
ALTER TABLE `content_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `employee`
--
ALTER TABLE `employee`
  MODIFY `id_admin` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `flower_workshop`
--
ALTER TABLE `flower_workshop`
  MODIFY `id_work` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `gender`
--
ALTER TABLE `gender`
  MODIFY `id_gender` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `messaging`
--
ALTER TABLE `messaging`
  MODIFY `id_message` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `messaging_thread`
--
ALTER TABLE `messaging_thread`
  MODIFY `id_thread_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `method_delivery`
--
ALTER TABLE `method_delivery`
  MODIFY `id_delivery_method` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `method_payment`
--
ALTER TABLE `method_payment`
  MODIFY `id_payment_method` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `order_detail_history`
--
ALTER TABLE `order_detail_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `shop_event`
--
ALTER TABLE `shop_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `shop_service`
--
ALTER TABLE `shop_service`
  MODIFY `id_service` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `social_network`
--
ALTER TABLE `social_network`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `status_order`
--
ALTER TABLE `status_order`
  MODIFY `id_status` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `work_hour`
--
ALTER TABLE `work_hour`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
