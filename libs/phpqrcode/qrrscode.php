<?php

            $rs->genpoly = array_fill(0, $nroots+1, 0);
        
            $rs->fcr = $fcr;
            $rs->prim = $prim;
            $rs->nroots = $nroots;
            $rs->gfpoly = $gfpoly;

            /* Find prim-th root of 1, used in decoding */
            for($iprim=1;($iprim % $prim) != 0;$iprim += $rs->nn)
            ; // intentional empty-body loop!
            
            $rs->iprim = (int)($iprim / $prim);
            $rs->genpoly[0] = 1;
            
            for ($i = 0,$root=$fcr*$prim; $i < $nroots; $i++, $root += $prim) {
                $rs->genpoly[$i+1] = 1;

                // Multiply rs->genpoly[] by  @**(root + x)
                for ($j = $i; $j > 0; $j--) {
                    if ($rs->genpoly[$j] != 0) {
                        $rs->genpoly[$j] = $rs->genpoly[$j-1] ^ $rs->alpha_to[$rs->modnn($rs->index_of[$rs->genpoly[$j]] + $root)];
                    } else {
                        $rs->genpoly[$j] = $rs->genpoly[$j-1];
                    }
                }
                // rs->genpoly[0] can never be zero
                $rs->genpoly[0] = $rs->alpha_to[$rs->modnn($rs->index_of[$rs->genpoly[0]] + $root)];
            }
            
            // convert rs->genpoly[] to index form for quicker encoding
            for ($i = 0; $i <= $nroots; $i++)
                $rs->genpoly[$i] = $rs->index_of[$rs->genpoly[$i]];

            return $rs;
        }
        
        //----------------------------------------------------------------------
        public function encode_rs_char($data, &$parity)
        {
            $MM       =& $this->mm;
            $NN       =& $this->nn;
            $ALPHA_TO =& $this->alpha_to;
            $INDEX_OF =& $this->index_of;
            $GENPOLY  =& $this->genpoly;
            $NROOTS   =& $this->nroots;
            $FCR      =& $this->fcr;
            $PRIM     =& $this->prim;
            $IPRIM    =& $this->iprim;
            $PAD      =& $this->pad;
            $A0       =& $NN;

            $parity = array_fill(0, $NROOTS, 0);

            for($i=0; $i< ($NN-$NROOTS-$PAD); $i++) {
                
                $feedback = $INDEX_OF[$data[$i] ^ $parity[0]];
                if($feedback != $A0) {      
                    // feedback term is non-zero
            
                    // This line is unnecessary when GENPOLY[NROOTS] is unity, as it must
                    // always be for the polynomials constructed by init_rs()
                    $feedback = $this->modnn($NN - $GENPOLY[$NROOTS] + $feedback);
            
                    for($j=1;$j<$NROOTS;$j++) {
                        $parity[$j] ^= $ALPHA_TO[$this->modnn($feedback + $GENPOLY[$NROOTS-$j])];
                    }
                }
                
                // Shift 
                array_shift($parity);
                if($feedback != $A0) {
                    array_push($parity, $ALPHA_TO[$this->modnn($feedback + $GENPOLY[0])]);
                } else {
                    array_push($parity, 0);
                }
            }
        }
    }
    
    //##########################################################################
    
    class QRrs {
    
        public static $items = array();
        
        //----------------------------------------------------------------------
        public static function init_rs($symsize, $gfpoly, $fcr, $prim, $nroots, $pad)
        {
            foreach(self::$items as $rs) {
                if($rs->pad != $pad)       continue;
                if($rs->nroots != $nroots) continue;
                if($rs->mm != $symsize)    continue;
                if($rs->gfpoly != $gfpoly) continue;
                if($rs->fcr != $fcr)       continue;
                if($rs->prim != $prim)     continue;

                return $rs;
            }

            $rs = QRrsItem::init_rs_char($symsize, $gfpoly, $fcr, $prim, $nroots, $pad);
            array_unshift(self::$items, $rs);

            return $rs;
        }
    }
