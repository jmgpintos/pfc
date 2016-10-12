<?php

class categoriaModel extends Model
{

    protected $_lastID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTablaCategorias($table = null, $campos = array())
    {
        $sql = "SELECT a.id, a.nombre, b.nombre categoria_padre "
                . "FROM categoria a LEFT JOIN categoria b ON a.id_categoria=b.id "
                . "ORDER BY a.nombre, a.id_categoria";
//        $sql = "SELECT a.id, a.nombre, b.nombre categoria_padre "
//                . "FROM categoria a, categoria b "
//                . "WHERE a.id_categoria=b.id";

        return $this->getSQL($sql);
    }
    
    public function getImagesByCategory($idCategoria)
    {
        $SQL = "SELECT i.*, c.nombre categoria, l.nombre licencia "
                . "FROM imagen i, licencia l, categoria c "
                . "WHERE l.id=i.id_licencia AND c.id=i.id_categoria AND i.id_categoria = $idCategoria ORDER BY nombre; ";
        $row = $this->_db->query($SQL);
        
        return $row->fetchAll(PDO::FETCH_ASSOC);
        
    }
    public function getCategoryName($idCategoria)
    {
        $row = parent::getById('categoria', $idCategoria);
        
        return $row["nombre"];
        
    }

    public function poblarComboCategoria()
    {
        $sql = "SELECT id_categoria, id, nombre "
                . " FROM categoria  "
                . " ORDER BY nombre";

        $getTerms = $this->getSQL($sql);

        $terms = array();
        foreach ($getTerms as $key => $term) {
            $id_categoria = $term['id_categoria'] == '' ? 0 : $term['id_categoria'];
            $terms[$id_categoria][$term['id']] = $term;
        }
//        vardump($terms);
        $r = array(
            'id' => null,
            'nombre' => '/',
        );
        $t = $this->printTerms($terms);
        $x = array_unshift($t, $r);
//        vardumpy($t);
        return($t);
    }

    function printTerms($terms, $parent = 0, $deep = 0)
    {
        $r = array();
        if (count($terms[$parent]) > 0) {

            $indent = "";
            for ($i = 0; $i < $deep + 1; $i++) {
                $indent .= "---";
            }

            foreach ($terms[$parent] as $key => $term) {

                if (@count($terms[$term['id']]) > 0) {
                    $r[] = array(
                        'id' => $term['id'],
                        'nombre' => $indent . ' ' . $term['nombre'],
                    );
                    $r1 = $this->printTerms($terms, $term['id'], ($deep + 1));
                    foreach ($r1 as $value) {
                        array_push($r, $value);
                    }
                }
                else {
                    $r[] = array(
                        'id' => $term['id'],
                        'nombre' => $indent . ' ' . $term['nombre'],
                    );
                }
            }
        }
        return $r;
    }

    public function crear($total)
    {
//        $total = 1;
        for ($i = 0; $i < $total; $i++) {
            $r = rand(00000, 99999);
            $id_categoria_padre = $this->_getCategoriaPadre($i);
            $nombre = 'categoria_' . $id_categoria_padre . '-' . $r;
            $descripcion = $this->_nuevaDescripcion($i);
            $campos = array(
                'id_categoria' => $id_categoria_padre,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
            );
//            vardump($campos);
            $this->insertarRegistro('categoria', $campos);
        }
    }

    private function _nuevaDescripcion($i)
    {
        $desc = '';
        $words = $this->getWordsForDescription();
        $minWordsInDescription = 15;
        $maxWordsInDescription = 45;
        $r = rand($minWordsInDescription, $maxWordsInDescription);

        for ($i = 0; $i < $maxWordsInDescription; $i++) {
            $r1 = rand(0, count($words) - 1);
            $desc .= $words[$r1] . ' ';
        }

        return trim($desc);
    }

    public function _getCategoriaPadre($i)
    {
        $id = null;
        $sql = "SELECT id FROM categoria";
        $listaIds = $this->getSQL($sql);
        $r = rand(0, count($listaIds) - 1);
        if ($r % 2 == 0) {
            return null;
        }
        return $listaIds[$r]['id'];
    }

    public function getWordsForDescription()
    {
        return array('endotheliomyxoma', 'hyperosmic', 'bucolically', 'outwrite', 'arteriofibrosis', 'safen', 'pitiableness', 'pneumonopleuritis', 'servitress', 'sparable', 'ponderling', 'concretionary', 'bullocky', 'lapachol', 'fibrocystic', 'inflamer', 'anathematically', 'violability', 'nucellus', 'Erik', 'nonetheless', 'criminalistic', 'valor', 'subpedunculate', 'hairstreak', 'premiss', 'subcyanide', 'dade', 'toplike', 'recitalist', 'sarcoseptum', 'polymazia', 'complimentalness', 'current', 'hurriedness', 'thesauri', 'sparid', 'rageously', 'reintervention', 'inelegantly', 'Macrobiotus', 'uncivilization', 'Inkerman', 'coction', 'lamiter', 'unreaped', 'spectacle', 'laminarin', 'toxiinfectious', 'testimonial', 'scotoma', 'koniscope', 'stemmer', 'unafeard', 'unbundled', 'apozema', 'scorer', 'Avarish', 'hygroscopic', 'skyphos', 'symposiac', 'arundinaceous', 'Otolithidae', 'deserticolous', 'geomalism', 'nicotine', 'lustreless', 'barbatimao', 'Poephagus', 'myelocyst', 'croakiness', 'unbodily', 'Saviour', 'Shakespearean', 'sternutator', 'paratonic', 'mosasaurian', 'dactyliology', 'interpretability', 'uncognized', 'steatolytic', 'variometer', 'doggereler', 'unescheated', 'esterization', 'pingue', 'Reub', 'quizzee', 'premention', 'Darsonvalism', 'Macedonian', 'forthcut', 'vespid', 'ovivorous', 'quarantiner', 'bowlful', 'odious', 'shrive', 'unessence', 'cosuitor', 'Xiphydriidae', 'Lycoperdon', 'effeminatize', 'hyposternum', 'Castilleja', 'unillustrated', 'Turkey', 'ainsell', 'trigonite', 'winegrowing', 'capsulotome', 'Gloeosporium', 'erythrochroic', 'splachnaceous', 'urosomite', 'unedibleness', 'Lindera', 'apicultural', 'lumpishness', 'tetractinose', 'timpano', 'boce', 'Centaurea', 'dijudication', 'conferential', 'Cyclophorus', 'fogon', 'voodooistic', 'hereditism', 'semiofficial', 'bedeaf', 'glycosaemia', 'unsymmetry', 'paleography', 'cetomorphic', 'anallantoidean', 'rampick', 'paleoethnic', 'ribby', 'Tursiops', 'spooneyness', 'renocutaneous', 'sportance', 'retrue', 'metascutellar', 'blindly', 'hyalomelan', 'epexegetic', 'cyclobutane', 'tatchy', 'stotinka', 'overdure', 'triplane', 'rumorous', 'squilgee', 'outmove', 'waterboard', 'unburnt', 'occipitoposterior', 'detain', 'cometical', 'helide', 'imputation', 'superintolerable', 'micromesentery', 'iconomaticism', 'twinable', 'Munychion', 'venepuncture', 'unovervalued', 'calices', 'shiversome', 'clipt', 'exequatur', 'unomniscient', 'bilianic', 'stertor', 'dapperling', 'zymosthenic', 'chromogram', 'frutescence', 'Zamia', 'humanize', 'infuser', 'pseudochromesthesia', 'pneograph', 'diversifiability', 'sentimentless', 'unneighbored', 'scrivello', 'gollar', 'Macleaya', 'delabialization', 'untilting', 'misadapt', 'tonological', 'autochthony', 'boonk', 'colugo', 'Agalinis', 'Ernie', 'arthrodire', 'inducement', 'auricularis', 'flywort', 'uncarnate', 'khamsin', 'aerenterectasia', 'naphtha', 'warehou', 'cystodynia', 'Cyprinus', 'supportless', 'catheti', 'beal', 'saltant', 'axle', 'Opiconsivia', 'Cycadofilicales', 'Hypocreaceae', 'nonvesture', 'brigade', 'bespatterment', 'deviation', 'steamboatman', 'carried', 'fugitively', 'coreometer', 'perityphlitic', 'duncical', 'equibalance', 'telemetrical', 'aryl', 'Nachitoches', 'atomician', 'fibroreticulate', 'unsinfulness', 'auxochrome', 'calotte', 'anthranilic', 'transanimate', 'abampere', 'fatidic', 'polycratic', 'thecodont', 'radius', 'corposant', 'scribblemania', 'aerotactic', 'smeary', 'Bretwalda', 'statically', 'serration', 'preimmigration', 'unstraying', 'bittering', 'intermediacy', 'tetramethylene', 'unwarped', 'unconsidered', 'interpretorial', 'unminced', 'palacewards', 'cytopathology', 'expanded', 'footed', 'endomysium', 'Kauravas', 'picoline', 'amplidyne', 'pyrectic', 'agami', 'nefandousness', 'Cynosarges', 'confederater', 'uncompassionateness', 'scytitis', 'commensurately', 'Prudy', 'mesoscutum', 'clatch', 'idleful', 'corah', 'Fridila', 'Mayeye', 'Themis', 'Nilometer', 'responsibleness', 'polysyndetically', 'coppernose', 'Graminaceae', 'spoliary', 'delater', 'Crimean', 'shearsman', 'boxbush', 'Polarid', 'inventibility', 'firefanged', 'irretentive', 'quaintly', 'Cystignathidae', 'tellurism', 'yotacism', 'autobiography', 'crantara', 'drink', 'linkman', 'sturtite', 'sadly', 'virulency', 'ellagic');
    }

}
