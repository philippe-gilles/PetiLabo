<?php
	inclure_inc("html", "param");
	inclure_site("xml_site", "xml_style", "xml_texte", "xml_document", "xml_media", "xml_menu", "xml_page");
	inclure_site("xml_module_actu", "xml_module_resa");

	class moteur {
		// Propriétés
		protected $nom_domaine = null;protected $nom_page = null;
		protected $mobile = false;
		protected $html = null;
		protected $site = null;
		protected $texte = null;protected $style = null;protected $document = null;
		protected $media = null;protected $menu = null;	protected $page = null;
		protected $langue_page = null;
		protected $module_actu = null;protected $est_actu = false;protected $no_actu = 0;
		protected $module_resa = null;
		protected $polices = array();protected $police_par_defaut = null;
		protected $est_pied_interne = false;protected $est_pied_reduit = false;

		protected function charger_xml() {
			// Création des structures au niveau site
			$this->site = new xml_site();
			// Fichier general.xml
			$ret = $this->site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier "._XML_PATH._XML_GENERAL._XML_EXT."</p>\n";
				return null;
			}
			// Fichier site.xml
			$ret = $this->site->ouvrir(_XML_PATH._XML_SITE._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML site</p>\n";
				return null;
			}
			// Ouverture des modules facultatifs
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$this->module_actu = new xml_module_actu();
				$ret = $this->module_actu->ouvrir(_XML_PATH_MODULES._XML_MODULE_ACTU._XML_EXT);
			}
			if ($this->site->has_module(_SITE_MODULE_RESA)) {
				$this->module_resa = new xml_module_resa();
			}
			// Gestion des textes
			$this->texte = new xml_texte();
			// Création de la liste des langues utilisées sur le site
			$this->charger_liste_langues();
			// Lecture des textes pour le site et la page
			$ret = $this->texte->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_TEXTE._XML_EXT);
			$ret = $this->texte->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_TEXTE._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->texte->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_TEXTE._XML_EXT);
			}
			// Ouverture des styles
			$this->style = new xml_style();
			$ret = $this->style->ouvrir(_XML_PATH._XML_STYLE._XML_EXT);
			$ret = $this->style->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_STYLE._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->style->ouvrir(_XML_PATH_MODULES._XML_STYLE._XML_EXT);
			}
			// Ouverture des documents
			$this->document = new xml_document();
			$ret = $this->document->ouvrir(_XML_PATH._XML_DOCUMENT._XML_EXT);
			$ret = $this->document->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_DOCUMENT._XML_EXT);
			// Ouverture des médiathèques
			$this->media = new xml_media($this->mobile);
			$ret = $this->media->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_MEDIA._XML_EXT);
			$ret = $this->media->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_MEDIA._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->media->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_MEDIA._XML_EXT);
			}
			// Ouverture des menuthèques
			$this->menu = new xml_menu();
			$ret = $this->menu->ouvrir(_XML_PATH._XML_MENU._XML_EXT);
			$ret = $this->menu->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_MENU._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->menu->ouvrir(_XML_PATH_MODULES._XML_MENU._XML_EXT);
			}
			// Ouverture du fichier XML page
			$this->page = new xml_page();
			$ret = $this->page->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_PAGE._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML page</p>\n";
				return null;
			}
			// Positionnement du drapeau "interne/externe"
			$this->est_pied_interne = (strcmp($this->site->get_pied_de_page(),_SITE_PIED_DE_PAGE_INTERNE))?false:true;
			$this->est_pied_reduit = (strcmp($this->site->get_pied_de_page(),_SITE_PIED_DE_PAGE_REDUIT))?false:true;
			// Création de l'utilitaire html
			$this->html = new html($this->mobile, $this->est_pied_interne, $this->est_pied_reduit);
		}
		protected function charger_xml_css($admin = false) {
			// Surcharge du fichier CSS standard
			$this->html->charger_xml_css();
			// CSS général
			$css = "";
			if ($this->site) {$css .= $this->site->extraire_css($this->mobile);}
			if ($this->style) {$css .= $this->style->extraire_css();}
			// Si c'est pour administration on désactive les hover
			if ($this->menu) {$css .= $this->menu->extraire_css(!($admin));}
			if ($this->media) {$css .= $this->media->extraire_css();}
			$this->html->ecrire_css($css);
			// Spécifique IE7/IE8 (quand site non mobile)
			if (!($this->mobile)) {
				$css_ie = "";
				if ($this->site) {$css_ie .= $this->site->extraire_css_ie();}
				if ($this->style) {$css_ie .= $this->style->extraire_css_ie();}
				$this->html->ecrire_css_ie($css_ie);
			}
		}
		protected function charger_police($nom_style) {
			if (strlen($nom_style) > 0) {
				$style = $this->style->get_style_texte($nom_style);
				if ($style) {
					$police = $style->get_police();
					if ((strlen($police) > 0) && (strcmp(trim(strtolower($police)), "serif"))) {
						// On vérifie que la police n'a pas été déjà chargée
						if (!(in_array($police, $this->polices))) {
							$this->html->charger_police($police);
							$this->polices[] = $police;
						}
					}
				}
			}
		}
		protected function charger_polices_par_defaut() {
			// Chargement des polices utilisées dans les styles
			$police_h1 = $this->site->get_style_titre_1();
			$this->charger_police($police_h1);
			$police_h2 = $this->site->get_style_titre_2();
			$this->charger_police($police_h2);
			$police_h3 = $this->site->get_style_titre_3();
			$this->charger_police($police_h3);
			$police_p = $this->site->get_style_paragraphe();
			$this->charger_police($police_p);
			// Au passage on récupère la police par défaut
			if (strlen($police_p) > 0) {
				$style = $this->style->get_style_texte($police_p);
				if ($style) {
					$police = $style->get_police();
					if (strlen($police) > 0) {
						$this->police_par_defaut = $police;
					}
				}
			}
		}
		// Cette méthode se positionne sur un bloc et parcourt son contenu
		// Pour chaque type de contenu une méthode d'écriture est appelée
		// Les méthodes d'écriture se contentent de parser les paramètres
		// propres à ce type de contenu afin de les transmettre à des  
		// méthodes génériques qui sont à implémenter dans les classes filles
		protected function ecrire_bloc(&$obj_bloc, $cpt_cont, $cpt_bloc) {
			$repere = $obj_bloc->get_repere();
			if ($repere) {
				$this->page->pointer_sur_bloc($repere);
				$nb_elems = $obj_bloc->get_nb_elems();
				for ($cpt_elem = 0;$cpt_elem < $nb_elems;$cpt_elem++) {
					$balise = $obj_bloc->get_elem($cpt_elem);
					$occ = $obj_bloc->get_idx_elem($cpt_elem);
					try {
						$fonction = "ecrire_bloc_".$balise;
						$this->$fonction($occ);
					}
					catch (Exception $e) {
						echo $e->getMessage(), "\n";
					}
				}
			}
		}

		// Ecriture des titres : parsing + appel à la fonction fille
		protected function ecrire_bloc_titre($occ) {
			$id_valeur = $this->page->lire_valeur_n(_PAGE_TITRE, $occ);
			$id_texte = $this->parser_id_crochets_actu($id_valeur);
			// Lecture de l'attribut "niveau"
			$niveau = (int) $this->page->lire_attribut_n(_PAGE_TITRE, $occ, _PAGE_ATTR_NIVEAU_TITRE);
			$niveau = ($niveau)?$niveau:1;
			$niveau = ($niveau < 1)?1:$niveau;
			$niveau = ($niveau > 3)?3:$niveau;
			// Lecture de l'attribut "style"
			$style_inline = $this->page->lire_attribut_n(_PAGE_TITRE, $occ, _PAGE_ATTR_STYLE_PARAGRAPHE);
			$style_titre = (strlen($style_inline) > 0)?$style_inline:$this->site->get_style_titre($niveau);
			$this->ecrire_titre($niveau, $style_titre, $id_texte);
		}
		protected function ecrire_titre($niveau, $style_titre, $id_texte) {return true;}
		protected function ecrire_bloc_titre_bandeau($occ) {return true;}

		// Ecriture des paragraphes : parsing + appel à la fonction fille
		protected function ecrire_bloc_paragraphe($occ) {
			$id_valeur = $this->page->lire_valeur_n(_PAGE_PARAGRAPHE, $occ);
			$id_texte = $this->parser_id_crochets_actu($id_valeur);
			// Lecture de l'attribut "style"
			$style_inline = $this->page->lire_attribut_n(_PAGE_PARAGRAPHE, $occ, _PAGE_ATTR_STYLE_PARAGRAPHE);
			$style = (strlen($style_inline) > 0)?$style_inline:$this->site->get_style_paragraphe();
			// Lecture du lien téléphonique
			$lien_telephonique = $this->page->lire_attribut_n(_PAGE_PARAGRAPHE, $occ, _PAGE_ATTR_LIEN_TELEPHONIQUE);
			$this->ecrire_paragraphe($style, $id_texte, $lien_telephonique);
		}
		protected function ecrire_paragraphe($style, $id_texte, $lien_telephonique) {return true;}

		// Ecriture des sauts : parsing + appel à la fonction fille
		protected function ecrire_bloc_saut($occ) {
			$h_saut = (float) $this->page->lire_valeur_n(_PAGE_SAUT, $occ);
			$this->ecrire_saut($h_saut);
		}
		protected function ecrire_saut($hauteur) {return true;}

		// Ecriture des images : parsing + appel à la fonction fille
		protected function ecrire_bloc_image($occ) {
			$valeur = $this->page->lire_valeur_n(_PAGE_IMAGE, $occ);
			$src = $this->parser_id_crochets_actu($valeur);
			$image = $this->media->get_image($src);
			if ($image) {
				// On récupère le alt éventuel
				$id_alt = $image->get_alt();
				// On vérifie s'il faut passer une légende ou non
				$has_legende = false;$niveau_legende = 0;
				$est_exterieur = false;$legende = "";
				$nom_style = "rscaption";
				$id_legende = $image->get_legende();
				if (strlen($id_legende) > 0) {
					$has_legende = true;
					$nom_style = $image->get_style_legende();
					if (strlen($nom_style) > 0) {
						$style = $this->media->get_style($nom_style);
						if ($style) {
							$niveau_legende = $style->get_niveau_titre();
							$est_exterieur = $style->get_est_exterieur();
							$survol = $style->get_survol();
							if ($survol) {
								// Le survol est désactivé pour la version mobile
								if (!($this->mobile)) {
									$nom_style .= " "._CSS_CLASSE_SURVOL;
								}
							}
							$style_texte = $style->get_style_texte();
							if (strlen($style_texte) > 0) {
								$nom_style .= " "._CSS_PREFIXE_TEXTE.$style_texte;
							}
						}
					}
				}
				$this->ecrire_image($image, $id_alt, $has_legende, $niveau_legende, $id_legende, $nom_style, $est_exterieur);
			}
		}
		protected function ecrire_image(&$image, $id_alt, $has_legende, $niveau_legende, $id_legende, $nom_style, $est_exterieur) {return true;}

		// Ecriture des diaporamas : parsing + appel aux fonctions filles (ouvrir, ajouter, fermer)
		protected function ecrire_bloc_diaporama($occ) {
			$val_gal = $this->page->lire_valeur_n(_PAGE_DIAPORAMA, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$gal = $this->media->get_galerie($nom_gal);
			if ($gal) {
				$nb_images = $gal->get_nb_elems();
				$navigation = $this->page->lire_attribut_n(_PAGE_DIAPORAMA, $occ, _MEDIA_ATTR_NAVIGATION);
				$has_navigation = (!(strcmp(trim(strtolower($navigation)), _XML_TRUE)))?true:false;
				$boutons = $this->page->lire_attribut_n(_PAGE_DIAPORAMA, $occ, _MEDIA_ATTR_BOUTONS);
				$has_boutons = (!(strcmp(trim(strtolower($boutons)), _XML_TRUE)))?true:false;
				// Détection préalable de la largeur max
				$largeur_max = 0;
				for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
					$nom_image = $gal->get_elem($cpt_img);
					$image = $this->media->get_image($nom_image);
					if ($image) {
						$largeur = $image->get_width();
						$largeur_max = ($largeur > $largeur_max)?$largeur:$largeur_max;
					}
				}
				$this->ouvrir_diaporama($nom_gal, $largeur_max);
				for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
					$nom_image = $gal->get_elem($cpt_img);
					$image = $this->media->get_image($nom_image);
					if ($image) {
						// On récupère le alt éventuel
						$id_alt = $image->get_alt();
						// On vérifie s'il faut passer une légende ou non
						$has_legende = false;
						$est_exterieur = false;
						$nom_style = "rscaption";
						$id_legende = $image->get_legende();
						if (strlen($id_legende) > 0) {
							$has_legende = true;
							$nom_style = $image->get_style_legende();
							$est_exterieur = false;
							if (strlen($nom_style) > 0) {
								$style = $this->media->get_style($nom_style);
								if ($style) {
									$survol = $style->get_survol();
									if ($survol) {
										// Le survol est désactivé pour la version mobile
										if (!($this->mobile)) {
											$nom_style .= " "._CSS_CLASSE_SURVOL;
										}
									}
									$style_texte = $style->get_style_texte();
									if (strlen($style_texte) > 0) {
										$nom_style .= " "._CSS_PREFIXE_TEXTE.$style_texte;
									}
									$est_exterieur = $style->get_est_exterieur();
								}
							}
						}
						$this->ajouter_diaporama($image, $id_alt, $has_legende, $id_legende, $nom_style, $est_exterieur);
					}
				}
				$this->fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $largeur_max);
			}
		}
		protected function ouvrir_diaporama($nom_gal, $largeur_max) {return true;}
		protected function ajouter_diaporama(&$image, $id_alt, $has_legende, $id_legende, $nom_style, $est_exterieur) {return true;}
		protected function fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $largeur_max) {return true;}

		// Ecriture des vignettes : parsing + appel aux fonctions filles (ouvrir, ajouter, fermer)
		protected function ecrire_bloc_vignettes($occ) {
			$val_gal = $this->page->lire_valeur_n(_PAGE_VIGNETTES, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$nb_cols = $this->page->lire_attribut_n(_PAGE_VIGNETTES, $occ, _PAGE_ATTR_NBCOLS_VIGNETTE);
			$nb_cols = ($nb_cols)?$nb_cols:1;
			$nb_cols = ($nb_cols < 1)?1:$nb_cols;
			$gal = $this->media->get_galerie($nom_gal);
			if ($gal) {
				$this->ouvrir_vignettes($nom_gal);
				$nb_images = $gal->get_nb_elems();
				for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
					$nom_image = $gal->get_elem($cpt_img);
					$image = $this->media->get_image($nom_image);
					if ($image) {
						$id_legende = $image->get_legende();
						$src_vignette = ($image->get_est_vide())?null:$image->get_src_reduite();
						$this->ajouter_vignette($nom_image, $src_vignette, $image->get_src(), $id_legende, $nb_cols);
					}
				}
				$this->fermer_vignettes($nom_gal);
			}
		}
		protected function ouvrir_vignettes($nom_gal) {return true;}
		protected function ajouter_vignette($nom_image, $src, $lien, $id_info, $nb_cols) {return true;}
		protected function fermer_vignettes($nom_gal) {return true;}

		// Ecriture des galeries : parsing + appel aux fonctions filles (ouvrir, ajouter, fermer)
		protected function ecrire_bloc_galerie($occ) {
			$val_gal = $this->page->lire_valeur_n(_PAGE_GALERIE, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$nb_cols = $this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _PAGE_ATTR_NBCOLS_GALERIE);
			$nb_cols = ($nb_cols)?$nb_cols:1;
			$nb_cols = ($nb_cols < 1)?1:$nb_cols;
			// On force la position haut pour la version mobile
			$position = ($this->mobile)?_PAGE_ATTR_POSITION_HAUT:$this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _PAGE_ATTR_POSITION_GALERIE);
			switch ($position) {
				case _PAGE_ATTR_POSITION_DROITE :
					$this->ouvrir_galerie($nom_gal, true);
					$this->ecrire_onglet_galerie($nom_gal, $nb_cols, true);
					$this->ecrire_vue_galerie($nom_gal, true);
					$this->fermer_galerie($nom_gal, true);
					break;
				case _PAGE_ATTR_POSITION_BAS :
					$this->ouvrir_galerie($nom_gal, false);
					$this->ecrire_onglet_galerie($nom_gal, $nb_cols, false);
					$this->ecrire_vue_galerie($nom_gal, false);
					$this->fermer_galerie($nom_gal, false);
					break;
				case _PAGE_ATTR_POSITION_GAUCHE :
					$this->ouvrir_galerie($nom_gal, true);
					$this->ecrire_vue_galerie($nom_gal, true);
					$this->ecrire_onglet_galerie($nom_gal, $nb_cols, true);
					$this->fermer_galerie($nom_gal, true);
					break;
				case _PAGE_ATTR_POSITION_HAUT :
				default :
					$this->ouvrir_galerie($nom_gal, false);
					$this->ecrire_vue_galerie($nom_gal, false);
					$this->ecrire_onglet_galerie($nom_gal, $nb_cols, false);
					$this->fermer_galerie($nom_gal, false);
					break;
			}
		}
		protected function ecrire_vue_galerie($nom_gal, $vertical) {
			$gal = $this->media->get_galerie($nom_gal);
			if ($gal) {
				$nb_images = $gal->get_nb_elems();
				if ($nb_images > 0) {
					$nom_image_init = $gal->get_elem(0);
					$image_init = $this->media->get_image($nom_image_init);
					$this->ouvrir_vue_galerie($nom_gal, $image_init, $vertical);
					$nb_images = $gal->get_nb_elems();
					for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
						$nom_image = $gal->get_elem($cpt_img);
						$image = $this->media->get_image($nom_image);
						if (($image) && (!($image->get_est_vide()))) {
							$id_legende = $image->get_legende();
							if (strlen($id_legende) > 0) {
								$nom_style = $image->get_style_legende();
								if (strlen($nom_style) > 0) {
									$style = $this->media->get_style($nom_style);
									if ($style) {
										$survol = $style->get_survol();
										if ($survol) {
											// Le survol est désactivé pour la version mobile
											if (!($this->mobile)) {
												$nom_style .= " "._CSS_CLASSE_SURVOL;
											}
										}
										$style_texte = $style->get_style_texte();
										if (strlen($style_texte) > 0) {
											$nom_style .= " "._CSS_PREFIXE_TEXTE.$style_texte;
										}
									}
								}
								else {
									$nom_style = "rscaption";
								}
								$this->ajouter_legende_galerie($nom_gal, $id_legende, $nom_style, $cpt_img);
							}
						}
					}
					$this->fermer_vue_galerie($nom_gal);
				}
			}
		}
		protected function ecrire_onglet_galerie($nom_gal, $nb_cols, $vertical) {
			$gal = $this->media->get_galerie($nom_gal);
			if ($gal) {
				$nb_images = $gal->get_nb_elems();
				if ($nb_images > 0) {
					$this->ouvrir_onglet_galerie($nom_gal, $vertical);
					for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
						$nom_image = $gal->get_elem($cpt_img);
						$image = $this->media->get_image($nom_image);
						if ($image) {
							$id_alt = $image->get_alt();
							$this->ajouter_onglet_galerie($nom_gal, $image, $id_alt, $cpt_img, $nb_cols);
						}
					}
					$this->fermer_onglet_galerie($nom_gal);
				}
			}
		}
		protected function ouvrir_galerie($nom_gal, $vertical) {return true;}
		protected function ouvrir_vue_galerie($nom_gal, &$image_init, $vertical) {return true;}
		protected function ajouter_legende_galerie($nom_gal, $id_legende, $nom_style, $index) {return true;}
		protected function fermer_vue_galerie($nom_gal) {return true;}
		protected function ouvrir_onglet_galerie($nom_gal, $vertical) {return true;}
		protected function ajouter_onglet_galerie($nom_gal, &$image, $id_alt, $index, $nb_cols) {return true;}
		protected function fermer_onglet_galerie($nom_gal) {return true;}
		protected function fermer_galerie($nom_gal, $vertical) {return true;}

		// Ecriture des menus : parsing + appel aux fonctions filles (ouvrir, ajouter, fermer)
		protected function ecrire_bloc_menu($occ) {
			$val_menu = $this->page->lire_valeur_n(_PAGE_MENU, $occ);
			$nom_menu = $this->parser_id_crochets_actu($val_menu);
			$alignement = $this->page->lire_attribut_n(_PAGE_MENU, $occ, _PAGE_ATTR_ALIGNEMENT);
			$menu = $this->menu->get_menu($nom_menu);
			if ($menu) {
				// Création du menu
				$nb_items_non_vides = $this->get_nb_items_non_vide($menu);
				$this->ouvrir_menu($nom_menu, $nb_items_non_vides, $alignement);
				$nb_items = $menu->get_nb_items();
				for ($cpt = 0;$cpt < $nb_items; $cpt++) {
					$cle_item = (string) $menu->get_item($cpt);
					$item = $this->menu->get_item($cle_item);
					if ($item) {
						$id_label_brut = $item->get_label();
						$id_label = $this->parser_id_crochets_actu($id_label_brut);
						$id_icone = $item->get_icone();
						$lien_brut = $item->get_lien();
						$lien_simple = $this->parser_lien_crochets_actu($lien_brut);
						$lien_editable = $item->get_lien_editable();
						$is_editable = (strlen($lien_editable) > 0)?true:false;
						if ($is_editable) {
							$lien = $lien_editable;
							$id_liste = $item->get_liste_cibles();
						}
						else {
							$lien = $lien_simple;
							$id_liste = null;
						}
						$id_info = $item->get_info();
						$style = $item->get_style();
						// On rajoute éventuellement le style texte
						$style_menu = $this->menu->get_style($style);
						if ($style_menu) {
							$style_texte = $style_menu->get_style_texte();
							if (strlen($style_texte) > 0) {
								$pref_style = (strlen($id_label) > 0)?_CSS_PREFIXE_TEXTE:_CSS_PREFIXE_ICONE;
								$style = $pref_style.$style_texte." "._CSS_PREFIXE_MENU.$style;
							}
						}
						$this->ajouter_menu($style, $id_icone, $id_label, $lien, $id_info, $is_editable, $id_liste);
					}
				}
				$this->fermer_menu($nb_items_non_vides);
			}
		}
		protected function ouvrir_menu($nom_menu, $nb_items_non_vides, $alignement) {return true;}
		protected function ajouter_menu($style, $has_icone, $id_label, $lien, $id_info, $is_editable, $id_liste) {return true;}
		protected function fermer_menu($nb_items_non_vide) {return true;}

		// Ecriture des plans : parsing + appel à la fonction fille
		protected function ecrire_bloc_carte($occ) {
			$id_texte = $this->page->lire_valeur_n(_PAGE_CARTE, $occ);
			$this->ecrire_plan($id_texte);
		}
		protected function ecrire_plan($id_texte) {return true;}

		// Ecriture des videos : parsing + appel à la fonction fille
		protected function ecrire_bloc_video($occ) {
			$source = $this->page->lire_attribut_n(_PAGE_VIDEO, $occ, _PAGE_ATTR_SOURCE_VIDEO);
			if (strlen($source) > 0) {
				$id_code = $this->page->lire_valeur_n(_PAGE_VIDEO, $occ);
				$this->ecrire_video($source, $id_code);
			}
		}
		protected function ecrire_video($source, $id_code) {return true;}

		// Ecriture des pièces jointes : parsing + appel à la fonction fille
		protected function ecrire_bloc_piece_jointe($occ) {
			$nom_pj = $this->page->lire_valeur_n(_PAGE_PJ, $occ);
			$pj = $this->document->get_document($nom_pj);
			if ($pj) {
				// Préparation du style
				$style = $this->site->get_style_paragraphe();
				// Lecture de l'attribut "lien"
				$type_lien = $this->page->lire_attribut_n(_PAGE_PJ, $occ, _PAGE_ATTR_LIEN_PJ);
				switch ($type_lien) {
					case _PAGE_ATTR_LIEN_IMAGE :
					case _PAGE_ATTR_LIEN_FICHIER :
						$lien = $type_lien;
						break;
					default :
						$lien = _PAGE_ATTR_LIEN_LEGENDE;
						break;
				}
				$fichier = $pj->get_fichier();
				$id_info = $pj->get_info();
				$id_legende = $pj->get_legende();
				$this->ecrire_pj($nom_pj, $lien, $style, $fichier, $id_info, $id_legende);
			}
		}
		protected function ecrire_pj($id_pj, $lien, $style, $fichier, $id_info, $id_legende) {return true;}

		// Ecriture du formulaire de contact
		protected function ecrire_bloc_formulaire_contact($occ) {
			$style = $this->page->lire_attribut_n(_PAGE_FORM_CONTACT, $occ, _PAGE_ATTR_FORMULAIRE_STYLE);
			$this->ecrire_form_contact($style);
		}
		protected function ecrire_form_contact($style) {return true;}
		
		// Ecriture des drapeaux : parsing + appel aux fonctions filles (ouvrir, ajouter, fermer)
		protected function ecrire_bloc_drapeaux($occ) {
			$nb_langues = $this->texte->get_nb_langues();
			// On n'affiche les drapeaux que s'il y a plus d'une langue
			if ($nb_langues > 1) {
				$alignement = $this->page->lire_attribut_n(_PAGE_DRAPEAUX, $occ, _PAGE_ATTR_ALIGNEMENT);
				$this->ouvrir_drapeaux($alignement);
				for ($cpt_langue = 0;$cpt_langue < $nb_langues;$cpt_langue++) {
					$langue = $this->texte->get_langue($cpt_langue);
					$nom = $this->texte->get_nom($langue);
					$pos = $this->texte->get_position($langue);
					$this->ajouter_drapeau($langue, $nom, $pos);
				}
				$this->fermer_drapeaux();
			}
		}
		protected function ouvrir_drapeaux($alignement) {return true;}
		protected function ajouter_drapeau($langue, $nom, $pos) {return true;}
		protected function fermer_drapeaux() {return true;}

		// Ecriture du plan du site
		protected function ecrire_bloc_plan_du_site() {
			$this->parcourir_plan_du_site(0, null);
			$this->ecrire_section_plan_du_site($this->texte->get_label_pied_de_page($this->langue_page));
			$this->ecrire_plan_pied_du_site($this->texte->get_label_mentions($this->langue_page), _HTML_PATH_MENTIONS_LEGALES, null);
			$this->ecrire_plan_pied_du_site($this->texte->get_label_credits($this->langue_page), _HTML_PATH_CREDITS, null);
			$this->ecrire_plan_pied_du_site($this->texte->get_label_plan($this->langue_page), _HTML_PATH_PLAN_DU_SITE, "0");
			$this->fermer_plan_du_site();
		}
		protected function parcourir_plan_du_site($niveau, $parent) {
			$nb_pages = $this->site->get_nb_pages();
			for ($cpt = 0;$cpt < $nb_pages;$cpt++) {
				$parent_page = $this->site->get_page_parent($cpt);
				if (!(strcmp($parent_page, $parent))) {
					$nom = $this->site->get_page_nom($cpt);
					$ref = $this->site->get_page_ref($cpt);
					$touche = $this->site->get_page_touche($cpt);
					$this->ecrire_plan_du_site($niveau, $nom, $ref, $touche);
					$this->parcourir_plan_du_site($niveau+1, $ref);
				}
			}
		}
		protected function ecrire_section_plan_du_site($nom) {return true;}
		protected function ecrire_plan_du_site($niveau, $nom, $ref, $touche) {return true;}
		protected function ecrire_plan_pied_du_site($nom, $ref, $touche) {return true;}
		protected function fermer_plan_du_site() {return true;}

		// Ecriture des crédits
		protected function ecrire_bloc_credits($occ) {
			$tab_credits = array("fa", "rs", "mp", "id", "ju", "jc", "te");
			$chapitre = $this->page->lire_valeur_n(_PAGE_CREDITS, $occ);
			if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_TECHNIQUE)))) {
				// On charge les textes supplémentaires pour les crédits techniques
				$ret = $this->texte->ouvrir(_XML_SOURCE_INTERNE, _XML_PATH_INTERNE._XML_CREDITS._XML_EXT);
				if ($ret) {
					if (strlen($chapitre) == 0) {$this->ouvrir_credit_section("credit_section_technique");}
					foreach ($tab_credits as $credit) {
						$id_titre = "credit_titre_".$credit;
						$id_lien = "credit_lien_".$credit;
						// Seul le texte est traductible
						$titre = $this->texte->get_texte($id_titre, $this->texte->get_langue_par_defaut());
						$lien = $this->texte->get_texte($id_lien, $this->texte->get_langue_par_defaut());
						$this->ecrire_credit_technique($titre, $lien, $credit, "credit_prefixe_lien");
					}
				}
				$this->fermer_credit_section();
			}
			if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_PHOTOGRAPHIQUE)))) {
				$taille_vignette = (int) $this->page->lire_attribut_n(_PAGE_CREDITS, $occ, _PAGE_ATTR_CREDITS_TAILLE);
				// On charge toutes les images du site pour les crédits photographiques
				$nb_pages = $this->site->get_nb_pages();
				for ($cpt = 0;$cpt < $nb_pages;$cpt++) {
					$ref = $this->site->get_page_ref($cpt);
					$nom = str_replace(_PXP_EXT, "", $ref);
					$src_media = _XML_PATH_PAGES.$nom."/"._XML_MEDIA._XML_EXT;
					$src_texte = _XML_PATH_PAGES.$nom."/"._XML_TEXTE._XML_EXT;
					$ret = $this->media->ouvrir(_XML_SOURCE_PAGE, $src_media, $cpt);
					$ret = $this->texte->ouvrir(_XML_SOURCE_PAGE, $src_texte, $cpt);
				}
				// Parcours des images pour repérer celles qui portent un copyright
				$nb_images = $this->media->get_nb_images();
				$nb_copy = 0;
				for ($cpt = 0;$cpt < $nb_images;$cpt++) {
					$image = $this->media->get_image_by_index($cpt);
					if ($image) {
						// Pas de copyright pour les images vides */
						$est_vide = $image->get_est_vide();
						if (!($est_vide)) {
							$id_copyright = $image->get_copyright();
							$src = $image->get_src_reduite();
							if ((strlen($id_copyright) > 0) && (strlen($src) > 0)) {
								// Si ce n'est déjà fait on ouvre la section crédits photo
								if ($nb_copy == 0) {
									if (strlen($chapitre) == 0) {$this->ouvrir_credit_section("credit_section_photo");}
									$nb_copy += 1;
								}
								// Les copyrights ne sont pas traduits
								$trad_copyright = trim($this->texte->get_texte($id_copyright, $this->texte->get_langue_par_defaut()));
								if (strlen($trad_copyright) > 0) {
									$largeur = $image->get_width();
									$hauteur = $image->get_height();
									$this->ecrire_credit_photo($src, $trad_copyright, $largeur, $hauteur, $taille_vignette);
								}
							}
						}
					}
				}
				// Si la section est ouverte on la referme
				if ($nb_copy > 0) {$this->fermer_credit_section();}
			}
		}
		protected function ouvrir_credit_section($id_texte) {return true;}
		protected function ecrire_credit_technique($titre, $lien, $id_credit, $id_visite) {return true;}
		protected function ecrire_credit_photo($src, $copyright, $largeur, $hauteur, $taille=0) {return true;}
		protected function fermer_credit_section() {return true;}

		// Ecriture des mentions légales
		protected function ecrire_bloc_mentions_legales($occ) {
			// On charge les textes supplémentaires pour les mentions légales
			$ret = $this->texte->ouvrir(_XML_SOURCE_INTERNE, _XML_PATH_INTERNE._XML_LEGAL._XML_EXT);
			if ($ret) {
				$chapitre = $this->page->lire_valeur_n(_PAGE_MENTIONS_LEGALES, $occ);

				// Section mentions légales
				if (strlen($chapitre) == 0) {$this->ouvrir_legal_section("legal_section_legale");}
				if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_LEGAL)))) {
					$this->ecrire_legal_mentions("legal_le_site", "legal_est_edite", "legal_responsable", "legal_hebergement");
				}
				if (strlen($chapitre) == 0) {$this->ouvrir_legal_section("legal_section_protection");}
				if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_PROTECTION)))) {
					$this->ecrire_legal_protection("legal_le_site", "legal_protection", "legal_cnil");
				}
				// Section cookies
				if (strlen($chapitre) == 0) {$this->ouvrir_legal_section("legal_section_cookies");}
				if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_COOKIES)))) {
					$this->ecrire_legal_cookies("legal_le_site", "legal_cookies");
				}
				// Section copyright
				if (strlen($chapitre) == 0) {$this->ouvrir_legal_section("legal_section_copyright");}
				if ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_COPYRIGHT)))) {
					$this->ecrire_legal_copyright("legal_propriete", "legal_reproduction", "legal_infraction");
				}
			}
		}
		protected function ouvrir_legal_section($id_texte) {return true;}
		protected function ecrire_legal_mentions($id_le_site, $id_est_edite, $id_resp, $id_hebergement) {return true;}
		protected function ecrire_legal_cookies($id_le_site, $id_cookies) {return true;}
		protected function ecrire_legal_copyright($id_propriete, $id_reproduction, $id_infraction) {return true;}
		protected function ecrire_legal_protection($site, $id_protection, $id_cnil) {return true;}

		// Boutons partage social
		protected function ecrire_bloc_partage_social($occ) {
			$forme = $this->page->lire_valeur_n(_PAGE_ADDTHIS, $occ);
			if (strcmp($forme, _PAGE_ATTR_FORME_ROND)) {$forme = _PAGE_ATTR_FORME_CARRE;}
			// Lecture de l'attribut "taille"
			$taille = $this->page->lire_attribut_n(_PAGE_ADDTHIS, $occ, _PAGE_ATTR_ADDTHIS_TAILLE);
			if ($taille <> 32) {$taille = 48;}
			$this->ecrire_addthis($forme, $taille);
		}
		protected function ecrire_addthis($forme, $taille) {return true;}
		// Ecriture d'un calendrier de réservation
		protected function ecrire_bloc_calendrier($occ) {
			if ($this->module_resa) {
				$id_cal = $this->page->lire_valeur_n(_PAGE_CALENDRIER_RESA, $occ);
				if (strlen($id_cal) > 0) {
					$ret = $this->module_resa->ouvrir($id_cal, _XML_PATH_MODULES.$id_cal."/"._XML_MODULE_RESA._XML_EXT);
					if ($ret) {
						$mois = (int) date("n");$an = (int) date("Y");
						$this->ouvrir_calendrier_resa($id_cal, $mois, $an);
						for ($cpt = 0;$cpt < 12;$cpt++) {
							$jour_sem = ((int) date("N", mktime(0, 0, 0, $mois, 1, $an)) - 1);
							$date_deb = mktime(0, 0, 0, $mois, (1 - $jour_sem), $an);
							$jour_deb = (int) date("j", $date_deb);
							$mois_deb = (int) date("n", $date_deb);
							$an_deb = (int) date("Y", $date_deb);
							$this->ecrire_calendrier_resa($id_cal, $jour_deb, $mois_deb, $an_deb, $mois, $an);
							$mois += 1;if ($mois == 13) {$mois = 1;$an += 1;}
						}
						$this->fermer_calendrier_resa($id_cal);
					}
				}
			}
		}
		protected function ouvrir_calendrier_resa($id_cal, $mois, $an) {return true;}
		protected function ecrire_calendrier_resa($id_cal, $jour_deb, $mois_deb, $an_deb, $mois, $an) {return true;}
		protected function fermer_calendrier_resa($id_cal) {return true;}
		// Ecriture de la bannière d'actualités
		protected function ecrire_bloc_banniere_actu($occ) {
			if ($this->module_actu) {
				$nb_actus = $this->page->lire_valeur_n(_PAGE_BANNIERE_ACTU, $occ);
				$style = $this->module_actu->get_style();
				// Détection préalable de la largeur max
				$largeur_max = 0;
				for ($cpt=0; $cpt<$nb_actus; $cpt++) {
					$no_actu = $this->module_actu->get_sommaire($cpt);
					$image = $this->media->get_image_actu($no_actu);
					if ($image) {
						$largeur = $image->get_width();
						$largeur_max = ($largeur > $largeur_max)?$largeur:$largeur_max;
					}
				}
				$this->ouvrir_banniere_actu($largeur_max);
				for ($cpt=0; $cpt<$nb_actus; $cpt++) {
					$no_actu = $this->module_actu->get_sommaire($cpt);
					$this->ecrire_banniere_actu($no_actu, $style);
				}
				$this->fermer_banniere_actu();
			}
		}
		protected function ouvrir_banniere_actu($largeur_max) {return true;}
		protected function ecrire_banniere_actu($no_actu, $style) {return true;}
		protected function fermer_banniere_actu() {return true;}

		// Préparation du bloc en fonction de son style
		protected function preparer_style_bloc(&$bloc, &$style) {
			if (($bloc) && ($style)) {
				$type_bordure = $style->get_type_bordure();
				if (!(strcmp($type_bordure, _STYLE_ATTR_TYPE_BORDURE_BANDEAU))) {
					$idx_premier_titre = $bloc->get_premier_titre();
					if ($idx_premier_titre >= 0) {
						$repere = $bloc->get_repere();
						if ($repere) {
							$this->page->pointer_sur_bloc($repere);
							$id_texte = $this->page->lire_valeur_n(_PAGE_TITRE, 0);
							if (strlen($id_texte) > 0) {
								$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
								$style->set_titre_bandeau($trad_texte);
								$style->set_style_titre_bandeau($this->site->get_style_titre(3));
								$bloc->set_balise_elem($idx_premier_titre, _PAGE_TITRE_BANDEAU);
							}
						}
					}
				}
			}
		}

		// Méthodes privées
		private function charger_liste_langues() {
			$nb_langues = $this->site->get_nb_langues();
			for ($cpt_langue = 0;$cpt_langue < $nb_langues;$cpt_langue++) {
				$code_langue = $this->site->get_code_langue($cpt_langue);
				$this->texte->ajouter_langue($code_langue);
			}
		}
		private function parser_id_crochets_actu($id_valeur) {
			$id_texte = $id_valeur;
			if ($this->module_actu) {
				$id_trim = trim($id_valeur);
				$crochets_n = !(strcmp("[n]", strtolower(substr($id_trim, -3))));
				if ($crochets_n) {
					$id_texte = substr($id_trim, 0, strlen($id_trim)-3).($this->no_actu);
				}
				else {
					$crochets_n_plus_1 = !(strcmp("[n+1]", strtolower(substr($id_trim, -5))));
					if ($crochets_n_plus_1) {
						$id_texte = substr($id_trim, 0, strlen($id_trim)-5).($this->module_actu->get_next_actu($this->no_actu));
					}
					else {
						$crochets_n_moins_1 = !(strcmp("[n-1]", strtolower(substr($id_trim, -5))));
						if ($crochets_n_moins_1) {
							$id_texte = substr($id_trim, 0, strlen($id_trim)-5).($this->module_actu->get_prev_actu($this->no_actu));
						}
					}
				}
			}
			return $id_texte;
		}
		private function parser_lien_crochets_actu($id_valeur) {
			$id_texte = $id_valeur;
			if ($this->module_actu) {
				$id_trim = trim($id_valeur);
				if (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->no_actu);
				}
				elseif (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n+1]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->module_actu->get_next_actu($this->no_actu));
				}
				elseif (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n-1]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->module_actu->get_prev_actu($this->no_actu));
				}
			}
			return $id_texte;
		}
		private function get_nb_items_non_vide(&$menu) {
			$ret = 0;
			if ($menu) {
				$nb_items = $menu->get_nb_items();
				if (!($menu->get_has_editable())) {
					$ret = $nb_items;
				}
				else {
					for ($cpt = 0;$cpt < $nb_items; $cpt++) {
						$cle_item = (string) $menu->get_item($cpt);
						$item = $this->menu->get_item($cle_item);
						if ($item) {
							$lien_simple = $item->get_lien();
							if (strlen($lien_simple) > 0) {
								$ret += 1;
							}
							else {
								$lien_editable = $item->get_lien_editable();
								if (strlen($lien_editable) > 0) {
									$lien = $this->texte->get_texte($lien_editable, $this->langue_page);
									if (strlen($lien) > 0) {
										$ret += 1;
									}
								}
							}
						}
					}
				}
			}
			return $ret;
		}
	}