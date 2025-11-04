<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VersetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $versets = [
            [
                'theme' => 'Foi',
                'versets' => [
                    ['reference' => 'Hébreux 11:1', 'texte' => 'Or la foi est une ferme assurance des choses qu’on espère, une démonstration de celles qu’on ne voit pas.'],
                    ['reference' => 'Hébreux 11:6', 'texte' => 'Or sans la foi il est impossible de lui être agréable; car il faut que celui qui s’approche de Dieu croie que Dieu existe, et qu’il est le rémunérateur de ceux qui le cherchent.'],
                    ['reference' => 'Marc 11:24', 'texte' => 'C’est pourquoi je vous dis: Tout ce que vous demanderez en priant, croyez que vous l’avez reçu, et vous le verrez s’accomplir.'],
                    ['reference' => 'Romains 10:17', 'texte' => 'Ainsi la foi vient de ce qu’on entend, et ce qu’on entend vient de la parole de Christ.'],
                    ['reference' => '2 Corinthiens 5:7', 'texte' => 'Car nous marchons par la foi et non par la vue.'],
                    ['reference' => 'Matthieu 17:20', 'texte' => 'C’est à cause de votre incrédulité, leur dit Jésus. Je vous le dis en vérité, si vous aviez de la foi comme un grain de sénevé, vous diriez à cette montagne: Transporte-toi d’ici là, et elle se transporterait; rien ne vous serait impossible.'],
                    ['reference' => 'Luc 1:37', 'texte' => 'Car rien n’est impossible à Dieu.'],
                    ['reference' => 'Jacques 1:6', 'texte' => 'Mais qu’il la demande avec foi, sans douter; car celui qui doute est semblable au flot de la mer, agité par le vent et poussé de côté et d’autre.'],
                    ['reference' => 'Proverbes 3:5-6', 'texte' => 'Confie-toi en l’Éternel de tout ton cœur, et ne t’appuie pas sur ta sagesse; reconnais-le dans toutes tes voies, et il aplanira tes sentiers.'],
                    ['reference' => 'Matthieu 21:22', 'texte' => 'Tout ce que vous demanderez avec foi par la prière, vous le recevrez.'],
                    ['reference' => 'Jean 14:1', 'texte' => 'Que votre cœur ne se trouble point. Croyez en Dieu, et croyez en moi.'],
                    ['reference' => 'Éphésiens 2:8', 'texte' => 'Car c’est par la grâce que vous êtes sauvés, par le moyen de la foi. Et cela ne vient pas de vous, c’est le don de Dieu.'],
                    ['reference' => 'Hébreux 12:2', 'texte' => 'Ayant les regards sur Jésus, le chef et le consommateur de la foi, qui, en vue de la joie qui lui était réservée, a souffert la croix, méprisé l’ignominie, et s’est assis à la droite du trône de Dieu.'],
                    ['reference' => 'Galates 2:20', 'texte' => 'J’ai été crucifié avec Christ; et si je vis, ce n’est plus moi qui vis, c’est Christ qui vit en moi; si je vis maintenant dans la chair, je vis dans la foi au Fils de Dieu, qui m’a aimé et qui s’est livré lui-même pour moi.'],
                    ['reference' => 'Romains 1:17', 'texte' => 'Parce qu’en lui est révélée la justice de Dieu par la foi et pour la foi, selon qu’il est écrit: Le juste vivra par la foi.'],
                    ['reference' => '1 Jean 5:4', 'texte' => 'Parce que tout ce qui est né de Dieu triomphe du monde; et la victoire qui triomphe du monde, c’est notre foi.'],
                    ['reference' => 'Jean 11:25', 'texte' => 'Jésus lui dit: Je suis la résurrection et la vie. Celui qui croit en moi vivra, quand même il serait mort.'],
                    ['reference' => 'Marc 9:23', 'texte' => 'Jésus lui dit: Si tu peux!… Tout est possible à celui qui croit.'],
                    ['reference' => 'Romains 8:28', 'texte' => 'Nous savons, du reste, que toutes choses concourent au bien de ceux qui aiment Dieu, de ceux qui sont appelés selon son dessein.'],
                    ['reference' => 'Psaume 37:5', 'texte' => 'Recommande ton sort à l’Éternel, mets en lui ta confiance, et il agira.'],
                ]
            ],
            [
                'theme' => 'Amour',
                'versets' => [
                    ['reference' => '1 Corinthiens 13:4-7', 'texte' => 'L’amour est patient, il est plein de bonté; l’amour n’est point envieux; l’amour ne se vante point, il ne s’enfle point d’orgueil, il ne fait rien de malhonnête, il ne cherche point son intérêt, il ne s’irrite point, il ne soupçonne point le mal, il ne se réjouit point de l’injustice, mais il se réjouit de la vérité; il excuse tout, il croit tout, il espère tout, il supporte tout.'],
                    ['reference' => '1 Jean 4:8', 'texte' => 'Celui qui n’aime pas n’a pas connu Dieu, car Dieu est amour.'],
                    ['reference' => 'Romains 8:38-39', 'texte' => 'Car j’ai l’assurance que ni la mort ni la vie, ni les anges ni les dominations, ni les choses présentes ni les choses à venir, ni les puissances, ni la hauteur, ni la profondeur, ni aucune autre créature ne pourra nous séparer de l’amour de Dieu manifesté en Jésus Christ notre Seigneur.'],
                    ['reference' => 'Colossiens 3:14', 'texte' => 'Mais par-dessus toutes ces choses revêtez-vous de l’amour, qui est le lien de la perfection.'],
                    ['reference' => '1 Pierre 4:8', 'texte' => 'Avant tout, ayez les uns pour les autres un ardent amour, car l’amour couvre une multitude de péchés.'],
                    ['reference' => 'Matthieu 22:37-39', 'texte' => 'Jésus lui répondit: Tu aimeras le Seigneur, ton Dieu, de tout ton cœur, de toute ton âme, et de toute ta pensée. C’est le premier et le plus grand commandement. Et voici le second, qui lui est semblable: Tu aimeras ton prochain comme toi-même.'],
                    ['reference' => 'Jean 3:16', 'texte' => 'Car Dieu a tant aimé le monde qu’il a donné son Fils unique, afin que quiconque croit en lui ne périsse point, mais qu’il ait la vie éternelle.'],
                    ['reference' => '1 Corinthiens 16:14', 'texte' => 'Que tout ce que vous faites se fasse avec amour.'],
                    ['reference' => 'Romains 12:9', 'texte' => 'Que l’amour soit sans hypocrisie. Ayez le mal en horreur; attachez-vous fortement au bien.'],
                    ['reference' => '1 Jean 4:18', 'texte' => 'La crainte n’est pas dans l’amour, mais l’amour parfait bannit la crainte; car la crainte suppose un châtiment, и celui qui craint n’est pas parfait dans l’amour.'],
                    ['reference' => 'Jean 13:34-35', 'texte' => 'Je vous donne un commandement nouveau: Aimez-vous les uns les autres; comme je vous ai aimés, vous aussi, aimez-vous les uns les autres. À ceci tous connaîtront que vous êtes mes disciples, si vous avez de l’amour les uns pour les autres.'],
                    ['reference' => '1 Corinthiens 13:13', 'texte' => 'Maintenant donc ces trois choses demeurent: la foi, l’espérance, l’amour; mais la plus grande de ces choses, c’est l’amour.'],
                    ['reference' => 'Romains 5:8', 'texte' => 'Mais Dieu prouve son amour envers nous, en ce que, lorsque nous étions encore des pécheurs, Christ est mort pour nous.'],
                    ['reference' => 'Éphésiens 5:2', 'texte' => 'Et marchez dans l’amour, à l’exemple de Christ, qui nous a aimés, et qui s’est livré lui-même à Dieu pour nous comme une offrande et un sacrifice de bonne odeur.'],
                    ['reference' => 'Matthieu 5:44', 'texte' => 'Mais moi, je vous dis: Aimez vos ennemis, bénissez ceux qui vous maudissent, faites du bien à ceux qui vous haïssent, et priez pour ceux qui vous maltraitent et qui vous persécutent.'],
                    ['reference' => '1 Jean 3:16', 'texte' => 'Nous avons connu l’amour, en ce qu’il a donné sa vie pour nous; nous aussi, nous devons donner notre vie pour les frères.'],
                    ['reference' => 'Proverbes 10:12', 'texte' => 'La haine excite des querelles, mais l’amour couvre toutes les fautes.'],
                    ['reference' => '1 Jean 4:19', 'texte' => 'Pour nous, nous l’aimons, parce qu’il nous a aimés le premier.'],
                    ['reference' => 'Jean 15:13', 'texte' => 'Il n’y a pas de plus grand amour que de donner sa vie for ses amis.'],
                    ['reference' => 'Galates 5:22', 'texte' => 'Mais le fruit de l’Esprit, c’est l’amour, la joie, la paix, la patience, la bonté, la bénignité, la fidélité.'],
                ]
            ],
            [
                'theme' => 'Espérance',
                'versets' => [
                    ['reference' => 'Romains 15:13', 'texte' => 'Que le Dieu de l’espérance vous remplisse de toute joie et de toute paix dans la foi, pour que vous abondiez en espérance, par la puissance du Saint Esprit!'],
                    ['reference' => 'Jérémie 29:11', 'texte' => 'Car je connais les projets que j’ai formés sur vous, dit l’Éternel, projets de paix et non de malheur, afin de vous donner un avenir et de l’espérance.'],
                    ['reference' => '1 Pierre 1:3', 'texte' => 'Béni soit Dieu, le Père de notre Seigneur Jésus Christ, qui, selon sa grande miséricorde, nous a régénérés, pour une espérance vivante, par la résurrection de Jésus Christ d’entre les morts.'],
                    ['reference' => 'Romains 5:5', 'texte' => 'Or, l’espérance ne trompe point, parce que l’amour de Dieu est répandu dans nos cœurs par le Saint Esprit qui nous a été donné.'],
                    ['reference' => 'Lamentations 3:24', 'texte' => 'L’Éternel est mon partage, dit mon âme; c’est pourquoi je veux espérer en lui.'],
                    ['reference' => 'Hébreux 6:19', 'texte' => 'Cette espérance, nous la possédons comme une ancre de l’âme, sûre et solide; elle pénètre au delà du voile.'],
                    ['reference' => 'Romains 8:24-25', 'texte' => 'Car c’est en espérance que nous sommes sauvés. Or, l’espérance qu’on voit n’est plus espérance: ce qu’on voit, peut-on l’espérer encore? Mais si nous espérons ce que nous ne voyons pas, nous l’attendons avec persévérance.'],
                    ['reference' => 'Psaume 130:5', 'texte' => 'J’espère en l’Éternel, mon âme espère, et j’attends sa promesse.'],
                    ['reference' => '2 Corinthiens 4:17-18', 'texte' => 'Car nos légères afflictions du moment présent produisent pour nous, au delà de toute mesure, un poids éternel de gloire, parce que nous regardons, non point aux choses visibles, mais à celles qui sont invisibles; car les choses visibles sont passagères, et les invisibles sont éternelles.'],
                    ['reference' => 'Romains 12:12', 'texte' => 'Réjouissez-vous en espérance. Soyez patients dans l’affliction. Persévérez dans la prière.'],
                    ['reference' => 'Psaume 119:114', 'texte' => 'Tu es mon asile et mon bouclier; j’espère en ta promesse.'],
                    ['reference' => 'Hébreux 10:23', 'texte' => 'Retenons fermement la profession de notre espérance, car celui qui a fait la promesse est fidèle.'],
                    ['reference' => 'Jean 16:33', 'texte' => 'Je vous ai dit ces choses, afin que vous ayez la paix en moi. Vous aurez des tribulations dans le monde; mais prenez courage, j’ai vaincu le monde.'],
                    ['reference' => 'Tite 2:13', 'texte' => 'En attendant la bienheureuse espérance, et la manifestation de la gloire de notre grand Dieu et Sauveur Jésus Christ.'],
                    ['reference' => 'Psaume 62:6', 'texte' => 'Oui, c’est en Dieu que mon âme se confie; de lui vient mon espérance.'],
                    ['reference' => 'Psaume 42:12', 'texte' => 'Pourquoi t’abats-tu, mon âme, et gémis-tu au dedans de moi? Espère en Dieu, car je le louerai encore; il est mon salut et mon Dieu.'],
                    ['reference' => '1 Timothée 4:10', 'texte' => 'Nous travaillons, en effet, et nous combattons, parce que nous mettons notre espérance dans le Dieu vivant, qui est le Sauveur de tous les hommes, principalement des croyants.'],
                    ['reference' => 'Psaume 39:8', 'texte' => 'Maintenant, Seigneur, que puis-je espérer? En toi est mon espérance.'],
                    ['reference' => 'Éphésiens 1:18', 'texte' => 'Et qu’il illumine les yeux de votre cœur, pour que vous sachiez quelle est l’espérance qui s’attache à son appel, quelle est la richesse de la gloire de son héritage qu’il réserve aux saints.'],
                    ['reference' => 'Lamentations 3:21', 'texte' => 'Voici ce que je veux repasser en mon cœur, ce qui me donnera de l’espérance.'],
                ]
            ],
            [
                'theme' => 'Paix',
                'versets' => [
                    ['reference' => 'Philippiens 4:7', 'texte' => 'Et la paix de Dieu, qui surpasse toute intelligence, gardera vos cœurs et vos pensées en Jésus Christ.'],
                    ['reference' => 'Jean 14:27', 'texte' => 'Je vous laisse la paix, je vous donne ma paix. Je ne vous donne pas comme le monde donne. Que votre cœur ne se trouble point, et ne s’alarme point.'],
                    ['reference' => 'Ésaïe 26:3', 'texte' => 'À celui qui est ferme dans ses sentiments tu assures la paix, la paix, parce qu’il se confie en toi.'],
                    ['reference' => 'Colossiens 3:15', 'texte' => 'Et que la paix de Christ, à laquelle vous avez été appelés pour former un seul corps, règne dans vos cœurs. Et soyez reconnaissants.'],
                    ['reference' => 'Psaume 4:9', 'texte' => 'Je me couche et je m’endors en paix, car toi seul, ô Éternel! tu me donnes la sécurité dans ma demeure.'],
                    ['reference' => 'Psaume 29:11', 'texte' => 'L’Éternel donnera la force à son peuple; l’Éternel bénira son peuple avec la paix.'],
                    ['reference' => 'Romains 5:1', 'texte' => 'Étant donc justifiés par la foi, nous avons la paix avec Dieu par notre Seigneur Jésus Christ.'],
                    ['reference' => 'Matthieu 11:28-29', 'texte' => 'Venez à moi, vous tous qui êtes fatigués et chargés, et je vous donnerai du repos. Prenez mon joug sur vous et recevez mes instructions, car je suis doux et humble de cœur; et vous trouverez du repos pour vos âmes.'],
                    ['reference' => 'Jean 16:33', 'texte' => 'Je vous ai dit ces choses, afin que vous ayez la paix en moi. Vous aurez des tribulations dans le monde; mais prenez courage, j’ai vaincu le monde.'],
                    ['reference' => 'Hébreux 12:14', 'texte' => 'Recherchez la paix avec tous, et la sanctification, sans laquelle personne ne verra le Seigneur.'],
                    ['reference' => 'Lévitique 26:6', 'texte' => 'Je mettrai la paix dans le pays, et personne ne troublera votre sommeil; je ferai disparaître du pays les bêtes féroces, et l’épée ne passera point par votre pays.'],
                    ['reference' => 'Psaume 119:165', 'texte' => 'Il y a beaucoup de paix pour ceux qui aiment ta loi, et il ne leur arrive aucun malheur.'],
                    ['reference' => 'Galates 5:22', 'texte' => 'Mais le fruit de l’Esprit, c’est l’amour, la joie, la paix, la patience, la bonté, la bénignité, la fidélité.'],
                    ['reference' => '2 Thessaloniciens 3:16', 'texte' => 'Que le Seigneur de la paix vous donne lui-même la paix en tout temps, de toute manière! Que le Seigneur soit avec vous tous!'],
                    ['reference' => 'Psaume 34:15', 'texte' => 'Détourne-toi du mal, et fais le bien; recherche et poursuis la paix.'],
                    ['reference' => 'Romains 14:19', 'texte' => 'Ainsi donc, recherchons ce qui contribue à la paix et à l’édification mutuelle.'],
                    ['reference' => 'Matthieu 5:9', 'texte' => 'Heureux ceux qui procurent la paix, car ils seront appelés fils de Dieu!'],
                    ['reference' => 'Proverbes 16:7', 'texte' => 'Quand l’Éternel approuve les voies d’un homme, il dispose favorablement à son égard même ses ennemis.'],
                    ['reference' => 'Psaume 85:9', 'texte' => 'J’écouterai ce que dit Dieu, l’Éternel; car il parle de paix à son peuple et à ses fidèles, pourvu qu’ils ne retournent pas à la folie.'],
                    ['reference' => 'Ésaïe 54:10', 'texte' => 'Quand les montagnes s’éloigneraient, quand les collines chancelleraient, mon amour ne s’éloignera point de toi, et mon alliance de paix ne chancellera point, dit l’Éternel, qui a compassion de toi.'],
                ]
            ],
            [
                'theme' => 'Courage',
                'versets' => [
                    ['reference' => 'Josué 1:9', 'texte' => 'Ne t’ai-je pas donné cet ordre: Fortifie-toi et prends courage? Ne t’effraie point et ne t’épouvante point, car l’Éternel, ton Dieu, est avec toi dans tout ce que tu entreprendras.'],
                    ['reference' => 'Ésaïe 41:10', 'texte' => 'Ne crains rien, car je suis avec toi; ne promène pas des regards inquiets, car je suis ton Dieu; je te fortifie, je viens à ton secours, je te soutiens de ma droite triomphante.'],
                    ['reference' => 'Psaume 27:1', 'texte' => 'L’Éternel est ma lumière et mon salut: de qui aurais-je crainte? L’Éternel est le soutien de ma vie: de qui aurais-je peur?'],
                    ['reference' => '2 Timothée 1:7', 'texte' => 'Car ce n’est pas un esprit de timidité que Dieu nous a donné, mais un esprit de force, d’amour et de sagesse.'],
                    ['reference' => 'Deutéronome 31:6', 'texte' => 'Fortifiez-vous et ayez du courage! Ne craignez point et ne soyez point effrayés devant eux; car l’Éternel, ton Dieu, marchera lui-même avec toi, il ne te délaissera point, il ne t’abandonnera point.'],
                    ['reference' => 'Philippiens 4:13', 'texte' => 'Je puis tout par celui qui me fortifie.'],
                    ['reference' => 'Psaume 31:25', 'texte' => 'Fortifiez-vous et que votre cœur s’affermisse, vous tous qui espérez en l’Éternel!'],
                    ['reference' => '1 Corinthiens 16:13', 'texte' => 'Veillez, demeurez fermes dans la foi, soyez des hommes, fortifiez-vous.'],
                    ['reference' => 'Psaume 118:6', 'texte' => 'L’Éternel est pour moi, je ne crains rien: que peuvent me faire des hommes?'],
                    ['reference' => 'Ésaïe 43:2', 'texte' => 'Si tu traverses les eaux, je serai avec toi; et les fleuves, ils ne te submergeront point; si tu marches dans le feu, tu ne te brûleras pas, et la flamme ne t’embrasera pas.'],
                    ['reference' => 'Psaume 23:4', 'texte' => 'Quand je marche dans la vallée de l’ombre de la mort, je ne crains aucun mal, car tu es avec moi: Ta houlette et ton bâton me rassurent.'],
                    ['reference' => 'Éphésiens 6:10', 'texte' => 'Au reste, fortifiez-vous dans le Seigneur, et par sa force toute-puissante.'],
                    ['reference' => 'Psaume 56:4', 'texte' => 'Je me glorifierai en Dieu, en sa parole; je me confie en Dieu, je ne crains rien: que peuvent me faire des hommes?'],
                    ['reference' => 'Hébreux 13:6', 'texte' => 'C’est donc avec assurance que nous pouvons dire: Le Seigneur est mon aide, je ne craindrai rien; que peut me faire un homme?'],
                    ['reference' => '1 Chroniques 28:20', 'texte' => 'David dit à son fils Salomon: Fortifie-toi, prends courage et agis; ne crains point, et ne t’effraie point. Car l’Éternel Dieu, mon Dieu, sera avec toi; il ne te délaissera point, il ne t’abandonnera point, jusqu’à ce que tout l’ouvrage pour le service de la maison de l’Éternel soit achevé.'],
                    ['reference' => 'Nahum 1:7', 'texte' => 'L’Éternel est bon, il est un refuge au jour de la détresse; et il connaît ceux qui se confient en lui.'],
                    ['reference' => 'Romains 8:31', 'texte' => 'Que dirons-nous donc à l’égard de ces choses? Si Dieu est pour nous, qui sera contre nous?'],
                    ['reference' => 'Psaume 121:7-8', 'texte' => 'L’Éternel te gardera de tout mal, il gardera ton âme; l’Éternel gardera ton départ et ton arrivée, dès maintenant et à jamais.'],
                    ['reference' => 'Jérémie 1:8', 'texte' => 'Ne les crains point, car je suis avec toi pour te délivrer, dit l’Éternel.'],
                    ['reference' => 'Ésaïe 12:2', 'texte' => 'Voici, Dieu est ma délivrance, je serai plein de confiance, et je ne craindrai rien; car l’Éternel, l’Éternel est ma force et le sujet de mes louanges; c’est lui qui m’a sauvé.'],
                ]
            ],
            [
                'theme' => 'Sagesse',
                'versets' => [
                    ['reference' => 'Jacques 1:5', 'texte' => 'Si quelqu’un d’entre vous manque de sagesse, qu’il la demande à Dieu, qui donne à tous simplement et sans reproche, et elle lui sera donnée.'],
                    ['reference' => 'Proverbes 9:10', 'texte' => 'Le commencement de la sagesse, c’est la crainte de l’Éternel; et la science des saints, c’est l’intelligence.'],
                    ['reference' => 'Proverbes 3:13', 'texte' => 'Heureux l’homme qui a trouvé la sagesse, et l’homme qui possède l’intelligence!'],
                    ['reference' => 'Proverbes 4:7', 'texte' => 'Voici le commencement de la sagesse: Acquiers la sagesse, et avec tout ce que tu possèdes acquiers l’intelligence.'],
                    ['reference' => 'Psaume 111:10', 'texte' => 'La crainte de l’Éternel est le commencement de la sagesse; tous ceux qui l’observent ont une raison saine. Sa gloire subsiste à jamais.'],
                    ['reference' => 'Colossiens 2:2-3', 'texte' => 'Afin qu’ils aient le cœur rempli de consolation, qu’ils soient unis dans l’amour, et enrichis d’une pleine intelligence pour connaître le mystère de Dieu, savoir Christ, mystère dans lequel sont cachés tous les trésors de la sagesse et de la science.'],
                    ['reference' => '1 Corinthiens 1:30', 'texte' => 'Or, c’est par lui que vous êtes en Jésus Christ, lequel, de par Dieu, a été fait pour nous sagesse, justice et sanctification et rédemption.'],
                    ['reference' => 'Proverbes 2:6', 'texte' => 'Car l’Éternel donne la sagesse; de sa bouche sortent la connaissance et l’intelligence.'],
                    ['reference' => 'Psaume 90:12', 'texte' => 'Enseigne-nous à bien compter nos jours, afin que nous appliquions notre cœur à la sagesse.'],
                    ['reference' => 'Jacques 3:17', 'texte' => 'La sagesse d’en haut est premièrement pure, ensuite pacifique, modérée, conciliante, pleine de miséricorde et de bons fruits, exempte de duplicité, d’hypocrisie.'],
                    ['reference' => 'Proverbes 16:16', 'texte' => 'Combien acquérir la sagesse vaut mieux que l’or! Combien acquérir l’intelligence est préférable à l’argent!'],
                    ['reference' => 'Ecclésiaste 7:12', 'texte' => 'Car à l’ombre de la sagesse on est abrité comme à l’ombre de l’argent; mais un avantage de la science, c’est que la sagesse fait vivre ceux qui la possèdent.'],
                    ['reference' => 'Proverbes 19:20', 'texte' => 'Écoute les conseils, et reçois l’instruction, afin que tu sois sage dans la suite de ta vie.'],
                    ['reference' => 'Job 28:28', 'texte' => 'Puis il dit à l’homme: Voici, la crainte du Seigneur, c’est la sagesse; s’éloigner du mal, c’est l’intelligence.'],
                    ['reference' => 'Romains 11:33', 'texte' => 'Ô profondeur de la richesse, de la sagesse et de la science de Dieu! Que ses jugements sont insondables, et ses voies incompréhensibles!'],
                    ['reference' => 'Proverbes 4:23', 'texte' => 'Garde ton cœur plus que toute autre chose, car de lui viennent les sources de la vie.'],
                    ['reference' => 'Éphésiens 5:15-16', 'texte' => 'Prenez donc garde de vous conduire avec circonspection, non comme des insensés, mais comme des sages; rachetez le temps, car les jours sont mauvais.'],
                    ['reference' => 'Proverbes 1:7', 'texte' => 'La crainte de l’Éternel est le commencement de la science; les insensés méprisent la sagesse et l’instruction.'],
                    ['reference' => 'Psaume 37:30', 'texte' => 'La bouche du juste annonce la sagesse, et sa langue proclame la justice.'],
                    ['reference' => 'Daniel 2:21', 'texte' => 'C’est lui qui change les temps et les circonstances, qui renverse et qui établit les rois, qui donne la sagesse aux sages et la science à ceux qui ont de l’intelligence.'],
                ]
            ],
            [
                'theme' => 'Pardon',
                'versets' => [
                    ['reference' => '1 Jean 1:9', 'texte' => 'Si nous confessons nos péchés, il est fidèle et juste pour nous les pardonner, et pour nous purifier de toute iniquité.'],
                    ['reference' => 'Matthieu 6:14-15', 'texte' => 'Si vous pardonnez aux hommes leurs offenses, votre Père céleste vous pardonnera aussi; mais si vous ne pardonnez pas aux hommes, votre Père ne vous pardonnera pas non plus vos offenses.'],
                    ['reference' => 'Éphésiens 4:32', 'texte' => 'Soyez bons les uns envers les autres, compatissants, vous pardonnant réciproquement, comme Dieu vous a pardonné en Christ.'],
                    ['reference' => 'Colossiens 3:13', 'texte' => 'Supportez-vous les uns les autres, et, si l’un a sujet de se plaindre de l’autre, pardonnez-vous réciproquement. De même que Christ vous a pardonné, pardonnez-vous aussi.'],
                    ['reference' => 'Psaume 103:12', 'texte' => 'Autant l’orient est éloigné de l’occident, autant il éloigne de nous nos transgressions.'],
                    ['reference' => 'Ésaïe 1:18', 'texte' => 'Venez et plaidons! dit l’Éternel. Si vos péchés sont comme le cramoisi, ils deviendront blancs comme la neige; s’ils sont rouges comme la pourpre, ils deviendront comme la laine.'],
                    ['reference' => 'Luc 6:37', 'texte' => 'Ne jugez point, et vous ne serez point jugés; ne condamnez point, et vous ne serez point condamnés; absolvez, et vous serez absous.'],
                    ['reference' => 'Michée 7:18-19', 'texte' => 'Quel Dieu est semblable à toi, qui pardonnes l’iniquité, qui oublies les péchés du reste de ton héritage? Il ne garde pas sa colère à toujours, car il prend plaisir à la miséricorde. Il aura encore compassion de nous, il mettra sous ses pieds nos iniquités; tu jetteras au fond de la mer tous leurs péchés.'],
                    ['reference' => 'Actes 3:19', 'texte' => 'Repentez-vous donc et convertissez-vous, pour que vos péchés soient effacés.'],
                    ['reference' => 'Psaume 86:5', 'texte' => 'Car tu es bon, Seigneur, tu pardonnes, tu es plein d’amour pour tous ceux qui t’invoquent.'],
                    ['reference' => 'Ésaïe 43:25', 'texte' => 'C’est moi, moi qui efface tes transgressions pour l’amour de moi, et je ne me souviendrai plus de tes péchés.'],
                    ['reference' => 'Daniel 9:9', 'texte' => 'Auprès du Seigneur, notre Dieu, la miséricorde et le pardon, car nous avons été rebelles envers lui.'],
                    ['reference' => 'Hébreux 8:12', 'texte' => 'Parce que je pardonnerai leurs iniquités, et que je ne me souviendrai plus de leurs péchés.'],
                    ['reference' => 'Luc 23:34', 'texte' => 'Jésus dit: Père, pardonne-leur, car ils ne savent ce qu’ils font.'],
                    ['reference' => 'Marc 11:25', 'texte' => 'Et, lorsque vous êtes debout faisant votre prière, si vous avez quelque chose contre quelqu’un, pardonnez, afin que votre Père qui est dans les cieux vous pardonne aussi vos offenses.'],
                    ['reference' => '2 Corinthiens 5:17', 'texte' => 'Si quelqu’un est en Christ, il est une nouvelle créature. Les choses anciennes sont passées; voici, toutes choses sont devenues nouvelles.'],
                    ['reference' => 'Proverbes 28:13', 'texte' => 'Celui qui cache ses transgressions ne prospère point, mais celui qui les avoue et les délaisse obtient miséricorde.'],
                    ['reference' => 'Psaume 32:1', 'texte' => 'Heureux celui à qui la transgression est remise, à qui le péché est pardonné!'],
                    ['reference' => 'Éphésiens 1:7', 'texte' => 'En lui nous avons la rédemption par son sang, la rémission des péchés, selon la richesse de sa grâce.'],
                    ['reference' => 'Romains 3:23-24', 'texte' => 'Car tous ont péché et sont privés de la gloire de Dieu; et ils sont gratuitement justifiés par sa grâce, par le moyen de la rédemption qui est en Jésus Christ.'],
                ]
            ],
            [
                'theme' => 'Joie',
                'versets' => [
                    ['reference' => 'Philippiens 4:4', 'texte' => 'Réjouissez-vous toujours dans le Seigneur; je le répète, réjouissez-vous.'],
                    ['reference' => 'Néhémie 8:10', 'texte' => 'Ne vous affligez pas, car la joie de l’Éternel sera votre force.'],
                    ['reference' => 'Psaume 118:24', 'texte' => 'C’est ici la journée que l’Éternel a faite: Qu’elle soit pour nous un sujet d’allégresse et de joie!'],
                    ['reference' => 'Psaume 16:11', 'texte' => 'Tu me feras connaître le sentier de la vie; il y a d’abondantes joies devant ta face, des délices éternelles à ta droite.'],
                    ['reference' => 'Jean 15:11', 'texte' => 'Je vous ai dit ces choses, afin que ma joie soit en vous, et que votre joie soit parfaite.'],
                    ['reference' => 'Galates 5:22-23', 'texte' => 'Mais le fruit de l’Esprit, c’est l’amour, la joie, la paix, la patience, la bonté, la bénignité, la fidélité, la douceur, la tempérance.'],
                    ['reference' => '1 Pierre 1:8', 'texte' => 'Lui que vous aimez sans l’avoir vu, en qui vous croyez sans le voir encore, vous réjouissant d’une joie ineffable et glorieuse.'],
                    ['reference' => 'Romains 15:13', 'texte' => 'Que le Dieu de l’espérance vous remplisse de toute joie et de toute paix dans la foi, pour que vous abondiez en espérance, par la puissance du Saint Esprit!'],
                    ['reference' => 'Psaume 30:6', 'texte' => 'Le soir arrivent les pleurs, et le matin l’allégresse.'],
                    ['reference' => 'Ecclésiaste 9:7', 'texte' => 'Va, mange avec joie ton pain, et bois gaiement ton vin; car dès longtemps Dieu prend plaisir à ce que tu fais.'],
                    ['reference' => 'Psaume 94:19', 'texte' => 'Quand les pensées s’agitent en foule au dedans de moi, tes consolations réjouissent mon âme.'],
                    ['reference' => 'Proverbes 17:22', 'texte' => 'Un cœur joyeux est un bon remède, mais un esprit abattu dessèche les os.'],
                    ['reference' => 'Psaume 51:14', 'texte' => 'Rends-moi la joie de ton salut, et qu’un esprit de bonne volonté me soutienne!'],
                    ['reference' => '1 Thessaloniciens 5:16-18', 'texte' => 'Soyez toujours joyeux. Priez sans cesse. Rendez grâces en toutes choses, car c’est à votre égard la volonté de Dieu en Jésus Christ.'],
                    ['reference' => 'Luc 15:10', 'texte' => 'De même, je vous le dis, il y a de la joie devant les anges de Dieu pour un seul pécheur qui se repent.'],
                    ['reference' => 'Jean 16:22', 'texte' => 'Vous donc aussi, vous êtes maintenant dans la tristesse; mais je vous reverrai, et votre cœur se réjouira, et nul ne vous ravira votre joie.'],
                    ['reference' => 'Psaume 126:5', 'texte' => 'Ceux qui sèment avec larmes moissonneront avec chants d’allégresse.'],
                    ['reference' => 'Romains 14:17', 'texte' => 'Car le royaume de Dieu, ce n’est pas le manger et le boire, mais la justice, la paix et la joie, par le Saint Esprit.'],
                    ['reference' => 'Psaume 37:4', 'texte' => 'Fais de l’Éternel tes délices, et il te donnera ce que ton cœur désire.'],
                    ['reference' => 'Ésaïe 61:10', 'texte' => 'Je me réjouirai en l’Éternel, mon âme sera ravie d’allégresse en mon Dieu; car il m’a revêtu des vêtements du salut, il m’a couvert du manteau de la délivrance.'],
                ]
            ],
            [
                'theme' => 'Protection',
                'versets' => [
                    ['reference' => 'Psaume 91:1-2', 'texte' => 'Celui qui demeure sous l’abri du Très Haut repose à l’ombre du Tout Puissant. Je dis à l’Éternel: Mon refuge et ma forteresse, mon Dieu en qui je me confie!'],
                    ['reference' => 'Proverbes 18:10', 'texte' => 'Le nom de l’Éternel est une tour forte; le juste s’y réfugie, et se trouve en sûreté.'],
                    ['reference' => 'Psaume 46:2', 'texte' => 'Dieu est pour nous un refuge et un appui, un secours qui ne manque jamais dans la détresse.'],
                    ['reference' => '2 Samuel 22:3', 'texte' => 'Dieu est mon rocher, où je trouve un abri, mon bouclier et la force qui me sauve, ma haute retraite et mon refuge. Ô mon Sauveur! tu me garantis de la violence.'],
                    ['reference' => 'Psaume 121:1-2', 'texte' => 'Je lève mes yeux vers les montagnes… D’où me viendra le secours? Le secours me vient de l’Éternel, qui a fait les cieux et la terre.'],
                    ['reference' => 'Ésaïe 54:17', 'texte' => 'Toute arme forgée contre toi sera sans effet; et toute langue qui s’élèvera en justice contre toi, tu la condamneras. Tel est l’héritage des serviteurs de l’Éternel, tel est le salut qui leur viendra de moi, dit l’Éternel.'],
                    ['reference' => 'Psaume 34:8', 'texte' => 'L’ange de l’Éternel campe autour de ceux qui le craignent, et il les arrache au danger.'],
                    ['reference' => '2 Thessaloniciens 3:3', 'texte' => 'Le Seigneur est fidèle, il vous affermira et vous préservera du malin.'],
                    ['reference' => 'Psaume 18:31', 'texte' => 'Car qui est Dieu, si ce n’est l’Éternel; et qui est un rocher, si ce n’est notre Dieu?'],
                    ['reference' => 'Deutéronome 31:8', 'texte' => 'L’Éternel marchera lui-même devant toi, il sera lui-même avec toi, il ne te délaissera point, il ne t’abandonnera point; ne crains point, et ne t’effraie point.'],
                    ['reference' => 'Psaume 138:7', 'texte' => 'Quand je marche au milieu de la détresse, tu me rends la vie, tu étends ta main sur la colère de mes ennemis, et ta droite me sauve.'],
                    ['reference' => 'Psaume 5:12', 'texte' => 'Car tu bénis le juste, ô Éternel! Tu l’entoures de ta grâce comme d’un bouclier.'],
                    ['reference' => 'Psaume 125:2', 'texte' => 'Des montagnes entourent Jérusalem; ainsi l’Éternel entoure son peuple, dès maintenant et à jamais.'],
                    ['reference' => 'Sophonie 3:17', 'texte' => 'L’Éternel, ton Dieu, est au milieu de toi, comme un héros qui sauve; il fera de toi sa plus grande joie; il gardera le silence dans son amour; il aura pour toi des transports d’allégresse.'],
                    ['reference' => 'Psaume 32:7', 'texte' => 'Tu es un asile pour moi, tu me garantis de la détresse, tu m’entoures de chants de délivrance.'],
                    ['reference' => 'Ésaïe 41:13', 'texte' => 'Car je suis l’Éternel, ton Dieu, qui fortifie ta droite, qui te dis: Ne crains rien, je viens à ton secours.'],
                    ['reference' => 'Psaume 20:2', 'texte' => 'Que l’Éternel t’exauce au jour de la détresse, que le nom du Dieu de Jacob te protège!'],
                    ['reference' => 'Psaume 119:114', 'texte' => 'Tu es mon asile et mon bouclier; j’espère en ta promesse.'],
                    ['reference' => '1 Pierre 5:7', 'texte' => 'Et déchargez-vous sur lui de tous vos soucis, car lui-même prend soin de vous.'],
                    ['reference' => 'Jean 10:28-29', 'texte' => 'Je leur donne la vie éternelle; et elles ne périront jamais, et personne ne les ravira de ma main. Mon Père, qui me les a données, est plus grand que tous; et personne ne peut les ravir de la main de mon Père.'],
                ]
            ],
            [
                'theme' => 'Persévérance',
                'versets' => [
                    ['reference' => 'Galates 6:9', 'texte' => 'Ne nous lassons pas de faire le bien; car nous moissonnerons au temps convenable, si nous ne nous relâchons pas.'],
                    ['reference' => 'Jacques 1:12', 'texte' => 'Heureux l’homme qui supporte patiemment la tentation; car, après avoir été éprouvé, il recevra la couronne de vie, que le Seigneur a promise à ceux qui l’aiment.'],
                    ['reference' => 'Romains 5:3-4', 'texte' => 'Bien plus, nous nous glorifions même des afflictions, sachant que l’affliction produit la persévérance, la persévérance la victoire dans l’épreuve, et cette victoire l’espérance.'],
                    ['reference' => 'Hébreux 12:1', 'texte' => 'Nous donc aussi, puisque nous sommes environnés d’une si grande nuée de témoins, rejetons tout fardeau, et le péché qui nous enveloppe si facilement, et courons avec persévérance dans la carrière qui nous est ouverte.'],
                    ['reference' => '2 Corinthiens 4:8-9', 'texte' => 'Nous sommes pressés de toute manière, mais non réduits à l’extrémité; dans la détresse, mais non dans le désespoir; persécutés, mais non abandonnés; abattus, mais non perdus.'],
                    ['reference' => 'Philippiens 3:14', 'texte' => 'Je cours vers le but, pour remporter le prix de la vocation céleste de Dieu en Jésus Christ.'],
                    ['reference' => 'Ésaïe 40:31', 'texte' => 'Mais ceux qui se confient en l’Éternel renouvellent leur force. Ils prennent le vol comme les aigles; ils courent, et ne se lassent point, ils marchent, et ne se fatiguent point.'],
                    ['reference' => 'Jacques 1:2-4', 'texte' => 'Mes frères, regardez comme un sujet de joie complète les diverses épreuves auxquelles vous pouvez être exposés, sachant que l’épreuve de votre foi produit la patience. Mais il faut que la patience accomplisse parfaitement son œuvre, afin que vous soyez parfaits et accomplis, sans faillir en rien.'],
                    ['reference' => '1 Corinthiens 15:58', 'texte' => 'Ainsi, mes frères bien-aimés, soyez fermes, inébranlables, travaillant de mieux en mieux à l’œuvre du Seigneur, sachant que votre travail ne sera pas vain dans le Seigneur.'],
                    ['reference' => '2 Timothée 4:7', 'texte' => 'J’ai combattu le bon combat, j’ai achevé la course, j’ai gardé la foi.'],
                    ['reference' => 'Apocalypse 2:10', 'texte' => 'Sois fidèle jusqu’à la mort, et je te donnerai la couronne de vie.'],
                    ['reference' => 'Colossiens 1:11', 'texte' => 'Fortifiés à tous égards par sa puissance glorieuse, en sorte que vous soyez toujours et avec joie persévérants et patients.'],
                    ['reference' => 'Luc 21:19', 'texte' => 'Par votre persévérance vous sauverez vos âmes.'],
                    ['reference' => 'Hébreux 10:36', 'texte' => 'Car vous avez besoin de persévérance, afin qu’après avoir accompli la volonté de Dieu, vous obteniez ce qui vous est promis.'],
                    ['reference' => 'Romains 8:37', 'texte' => 'Mais dans toutes ces choses nous sommes plus que vainqueurs par celui qui nous a aimés.'],
                    ['reference' => 'Jacques 5:11', 'texte' => 'Voici, nous disons bienheureux ceux qui ont souffert avec patience. Vous avez entendu parler de la patience de Job, et vous avez vu la fin que le Seigneur lui accorda, car le Seigneur est plein de miséricorde et de compassion.'],
                    ['reference' => '1 Pierre 5:10', 'texte' => 'Le Dieu de toute grâce, qui vous a appelés en Jésus Christ à sa gloire éternelle, après que vous aurez souffert un peu de temps, vous perfectionnera lui-même, vous affermira, vous fortifiera, vous rendra inébranlables.'],
                    ['reference' => '2 Pierre 1:5-6', 'texte' => 'À cause de cela même, faites tous vos efforts pour joindre à votre foi la vertu, à la vertu la science, à la science la tempérance, à la tempérance la patience, à la patience la piété.'],
                    ['reference' => 'Romains 12:12', 'texte' => 'Réjouissez-vous en espérance. Soyez patients dans l’affliction. Persévérez dans la prière.'],
                    ['reference' => 'Philippiens 1:6', 'texte' => 'Je suis persuadé que celui qui a commencé en vous cette bonne œuvre la rendra parfaite pour le jour de Jésus Christ.'],
                ]
            ],
        ];

        // Vider la table avant de la remplir pour éviter les doublons
        DB::table('versets')->truncate();

        foreach ($versets as $themeGroup) {
            foreach ($themeGroup['versets'] as $verset) {
                DB::table('versets')->insert([
                    'theme' => $themeGroup['theme'],
                    'reference' => $verset['reference'],
                    'texte' => $verset['texte'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}