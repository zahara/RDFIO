<?php

class RDFIORDFImporter {
	
	function __construct() {
	
	}

	public function importRdfXml( $importData ) {
		# Parse RDF/XML to triples
		$arc2rdfxmlparser = ARC2::getRDFXMLParser();
		$arc2rdfxmlparser->parseData( $importData );

		# Receive the data
		$triples = $arc2rdfxmlparser->triples;
		$tripleindex = $arc2rdfxmlparser->getSimpleIndex();
		$namespaces = $arc2rdfxmlparser->nsp;
		
		$this->importFromArc2Data( $triples, $tripleindex, $namespaces );
	}

	public function importTriples( $triples ) {
		$this->importFromArc2Data( $triples );
	}

	// TODO: Add other formats here later ...

	private function importFromArc2Data( $triples, $tripleIndex="", $namespaces="" ) {
		global $wgOut;
		
        # Parse data from ARC2 triples to custom data structure holding wiki pages
        $arc2towikiconverter = new RDFIOARC2ToWikiConverter();
        $wikipages = $arc2towikiconverter->convert( $triples, $tripleIndex, $namespaces );
        
        # Import pages into wiki
        $smwPageWriter = new RDFIOSMWPageWriter();
        $smwPageWriter->import( $wikipages );
	}

}

?>