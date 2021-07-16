$(document).ready(function() {
				
	var leftSidebar = {
		
		logo: {
			classes: ['dropdown','profile-element'],
			alt: 'Nimble Storage',
			img: 'company_specific_files/423/logo/logo.jpg'
		},
		categories: [{
			id: '1',
			icon: 'fa-th-large',
			label: 'ROI Sections',
			classes: ['smooth-scroll','active'],
			sections: [{
				id: '2',
				label: 'Inputs and Summary',
				href: '#section2'
			}]
		},{
			id: '1',
			icon: 'fa-th-large',
			label: 'Reference Tables',
			classes: ['smooth-scroll','active'],
			sections: [{
				id: '3',
				label: 'Dedupe Calculations',
				href: '#section3'
			},{
				id: '4',
				label: 'Details',
				href: '#section4'
			},{
				id: '5',
				label: 'Assumptions',
				href: '#section5'
			}]
		},{
			id: '4',
			icon: 'fa-goble',
			label: 'My ROIs',
			href: '../../dashboard'
		}]
	}
	
	var testInputs = {
		elements: [{
			type: 'section',
			id: 2,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'Inputs and Summary',
				style: 'margin-bottom: 20px;'
			}
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			elements: [{
				type: 'holder',
				classes: ['bottom-border','white-bg','dashboard-header','col-xs-12','col-sm-12','col-md-12','col-lg-12','margin-bottom-25'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-8'],
					elements: [{
						type: 'text',
						classes: ['caption-text'],
						tag: 'p',
						text: '<span style="font-size: 22px;">The Nimble Storage Predictive Flash platform efficiently delivers the benefits of primary storage, backup, and disaster recovery in an all-inclusive package to provide the ideal foundation for comprehensive data protection. And with InfoSight predictive analytics, you can be confident that your data protection strategy is working as expected through intuitive dashboards and proactive notifications.</span>'
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
					elements: [{
						type: 'video',
						src: '//www.youtube.com/embed/jjsHZ3dVXes'
					}]
				}]
			},{
				type: 'holder',
				classes: ['row','margin-bottom-25'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-6'], 
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type: 'holder',
							classes: ['form-horizontal'],
							elements: [{
								type: 'holder',
								classes: ['form-group'],
								elements: [{
									type: 'text',
									classes: ['subsection-header'],
									tag: 'div',
									text: '<h2>BASIC INPUTS</h2>'
								}]
							}]
						},{
							type:	'input',
							id:	'1',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label	:	{
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: 'Primary Data'
							},
							format: '0,0[.]00',
							append: 'TB',
							value: '30'
						},{
							type: 'input',
							id:	'2',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: 'Backup Retention'
							},
							format:	'0,0[.]00',
							append: 'Days',
							value: '30'
						},{
							type: 'text',
							tag: 'div',
							text: '<h3>Backup Management</h3>'
						},{
							type: 'input',
							id:	'3',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Hours/Week'
							},
							format:	'0,0[.]00',
							value: '10'
						},{
							type: 'input',
							id:	'4',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Costs/Hour'
							},
							format:	'$0,0[.]00',
							value: '30'
						},{
							type: 'input',
							id:	'5',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Converged Backup Opex Reduction'
							},
							format:	'0,0[.]00%',
							value: '80'
						},{
							type: 'input',
							id:	'6',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Data loss cost/hour'
							},
							popup: {
								text: 'Cost of losing/re-constructing data due to non-zero RPO'
							},
							format:	'$0,0[.]00',
							value: '250'
						},{
							type: 'input',
							id:	'7',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Annual number of restores from backup'
							},
							popup: {
								text: 'Number of times/year application or user data is restored from a backup system'
							},
							format:	'0,0[.]00',
							value: '4'
						},{
							type: 'dropdown',
							id:	'8',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: '<h3>Replication</h3>'
							},
							options: [
								{
									value: 'None',
									text: 'None'
								},{
									value: 'D2D Tier',
									text: 'D2D Tier'
								},{
									value: 'Primary Tier',
									text: 'Primary Tier'
								}
							]
						},{
							type: 'input',
							id:	'9',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Hourly cost of site downtime'
							},
							popup: {
								text: 'Hourly cost of application unavailability due to non-zero RTO'
							},
							format:	'$0,0[.]00',
							value: '20000'
						},{
							type: 'input',
							id:	'10',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Annual probability of site downtime'
							},
							popup: {
								text: 'Probability of data center failure / downtime'
							},
							format:	'0,0[.]00%',
							value: '2'
						},{
							type: 'text',
							tag: 'div',
							text: '<h3>Power/Cooling</h3>'
						},{
							type: 'input',
							id:	'11',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'cost/KWH'
							},
							format:	'$0,0[.]00',
							value: '0.12'
						},{
							type: 'input',
							id:	'12',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'cooling cost/KWH'
							},
							format:	'$0,0[.]00',
							value: '0.14'
						}]											
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-6'], 
					elements: [{						
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type:	'text',
							text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Summary Output</h2>'
						},{
							type: 'table',
							columns: [{
								title: {
									text: '&nbsp;'
								},
								rows: [{
									content: 'Hardware',
								},{
									content: 'Upgrades',
								},{
									content: 'Support',
								},{
									content: 'Power/Cooling',
								},{
									content: 'Backup Management Opex',
								},{
									content: 'Downtime/Restore Cost',
								},{
									content: 'Professional Services',
								},{
									content: 'Total',
								}]
							},{
								title: {
									text: 'Nimble'
								},
								rows: [{
									content: '$0',
									cell: {
										id: 'ST1',
										format: '($0,0)',
										formula: 'SUM( TAO1:TAO2 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST2',
										format: '($0,0)',
										formula: 'SUM( TAO3:TAU3 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST3',
										format: '($0,0)',
										formula: 'SUM( TAP1:TAU2 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST4',
										format: '($0,0)',
										formula: 'SUM( TAP7:TAU7 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST5',
										format: '($0,0)',
										formula: 'SUM( TAP4:TAU4 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST6',
										format: '($0,0)',
										formula: 'SUM( TAP5:TAU6 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST7',
										format: '($0,0)',
										formula: 'SUM( TAO8:TAU8 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'GT1',
										format: '($0,0)',
										formula: 'SUM( ST1:ST7 )'
									}
								}]
							},{
								title: {
									text: 'Alternative'
								},
								rows: [{
									content: '$0',
									cell: {
										id: 'ST8',
										format: '($0,0)',
										formula: 'SUM( TAV1:TAV3 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST9',
										format: '($0,0)',
										formula: 'SUM( TAW4:TBB4 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST10',
										format: '($0,0)',
										formula: 'SUM( TAW1:TBB3 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST11',
										format: '($0,0)',
										formula: 'SUM( TAW8:TBB8 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST12',
										format: '($0,0)',
										formula: 'SUM( TAW5:TBB5 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST13',
										format: '($0,0)',
										formula: 'SUM( TAW6:TBB7 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'ST14',
										format: '($0,0)',
										formula: 'SUM( TAV9:TBB9 )'
									}
								},{
									content: '$0',
									cell: {
										id: 'GT2',
										format: '($0,0)',
										formula: 'SUM( ST8:ST14 )'
									}
								}]
							}]	
						},{
							type: 'graph',
							id: '27',
							series: [{
								id: '48309',
								name: 'Professional Services',
								data: [{
									id: '130',
									equation: 'ST7'
								},{
									id: '131',
									equation: 'ST14'
								}]
							},{
								id: '48310',
								name: 'Downtime/Restore Cost',
								data: [{
									id: '130',
									equation: 'ST6'
								},{
									id: '131',
									equation: 'ST13'
								}]
							},{
								id: '48311',
								name: 'Backup Mgmt Opex',
								data: [{
									id: '130',
									equation: 'ST5'
								},{
									id: '131',
									equation: 'ST12'
								}]
							},{
								id: '48312',
								name: 'Power/Cooling',
								data: [{
									id: '130',
									equation: 'ST4'
								},{
									id: '131',
									equation: 'ST11'
								}]
							},{
								id: '48313',
								name: 'Support',
								data: [{
									id: '130',
									equation: 'ST3'
								},{
									id: '131',
									equation: 'ST10'
								}]
							},{
								id: '48314',
								name: 'Upgrades',
								data: [{
									id: '130',
									equation: 'ST2'
								},{
									id: '131',
									equation: 'ST9'
								}]
							},{
								id: '48315',
								name: 'Hardware',
								data: [{
									id: '130',
									equation: 'ST1'
								},{
									id: '131',
									equation: 'ST8'
								}]
							}]
						}]
					}]
				}]
			}]
		},{
			type: 'section',
			id: 3,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				text: '<h1 style="margin-bottom: 20px;">Dedupe Calculations'
			},
			permission: [{
				level: '2',
				visibility: 'hidden'
			}]
		},{
			type: 'holder',
			permission: [{
				level: '1',
				visibility: 'hidden'
			},{
				level: '2',
				visibility: 'hidden'
			}],
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			elements: [{
				type: 'holder',
				classes: ['row'],
				elements: [{
					type: 'holder',
					classes: ['row','margin-bottom-25'],
					elements: [{				
						type: 'holder',
						classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'], 
						elements: [{
							type: 'holder',
							elements: [{						
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type: 'table',
									columns: [{
										title: {
											text: 'Baseline'
										},
										rows: [{
											content: '100',
											cell: {
												type: 'input',
												id: 'TA1',
												format: '0,0[.]00'
											}
										}]
									},{
										title: {
											text: 'Change Rate'
										},
										rows: [{
											content: '2',
											cell: {
												type: 'input',
												id: 'TB1',
												format: '0,0.0%'
											}
										}]
									},{
										title: {
											text: 'Full Frequency'
										},
										rows: [{
											content: '7',
											cell: {
												type: 'input',
												id: 'TC1',
												format: '0,0[.]00'
											}
										}]
									},{
										title: {
											text: 'Days Skipped'
										},
										rows: [{
											content: '6',
											cell: {
												type: 'input',
												id: 'TD1',
												format: '0,0[.]00'
											}
										}]
									},{
										title: {
											text: '% Blocks Changed'
										},
										rows: [{
											content: '50.0',
											cell: {
												type: 'input',
												id: 'TE1',
												format: '0,0.0%'
											}
										}]
									},{
										title: {
											text: 'Dedupe Factor'
										},
										rows: [{
											content: '33',
											cell: {
												type: 'input',
												id: 'TF1',
												format: '0,0[.]00%'
											}
										}]
									},{
										title: {
											text: 'Weekend Change Rate'
										},
										rows: [{
											content: '0.0',
											cell: {
												type: 'input',
												id: 'TG1',
												format: '0,0.0%'
											}
										}]
									}]	
								}]
							}]
						}]
					}]
				},{
					type: 'holder',
					classes: ['row'],
					elements: [{				
						type: 'holder',
						classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
						elements: [{
							type: 'holder',
							elements: [{						
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type: 'text',
									classes: ['smooth-scroll'],
									text: '<h3 class="section-header" style="font-size: 24px;">Traditional</h3>'
								},{
									type: 'rowtable',
									specs: {
										pagination: true,
										searching: false,
										info: false,
										ordering: false
									},
									headers: [{
										title: {
											text: 'Day'
										}
									},{
										title: {
											text: 'Backup Size'
										}
									},{
										title: {
											text :'Cumulative'
										}
									}],
									rows: [{
										id: 2,
										cells: [{
											id: 'TA',
											format: '0,0[.]00',
											formula: '0',
											content: '0'
										},{
											id: 'TB',
											format: '0,0[.]00',
											formula: '"IF( MOD( TA" + ( id ) + ", TC1 ) = 0, TA1, IF( MOD( TA" + ( id ) + ", TD1 ) = 0, 0, TB1 * TA1 / TE1 ) )"',
											content: '0'
										},{
											id: 'TC',
											format: '0,0[.]00',
											formula: '"TB2"',
											content: '0'
										}]
									},{
										repeat: 100,
										id: 3,
										cells: [{
											id: 'TA',
											format: '0,0[.]00',
											formula: '( id - 2 )',
											content: '0'
										},{
											id: 'TB',
											format: '0,0[.]00',
											formula: '"IF( MOD( TA" + ( id ) + ", TC1 ) = 0, TA1, IF( MOD( TA" + ( id ) + ", TD1 ) = 0, 0, TB1 * TA1 / TE1 ) )"',
											content: '0'
										},{
											id: 'TC',
											format: '0,0[.]00',
											formula: '"TB" + ( id ) + " + TC" + ( id - 1 )',
											content: '0'
										}]
									}]	
								}]
							}]
						}]
					},{				
						type: 'holder',
						classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'], 
						elements: [{
							type: 'holder',
							elements: [{						
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type:	'text',
									classes: ['smooth-scroll'],
									text: '<h3 class="section-header" style="font-size: 24px;">Target Dedupe System</h3>'
								},{
									type: 'rowtable',
									specs: {
										pagination: true
									},
									headers: [{
										title: {
											text: 'DD'
										}
									},{
										title: {
											text: 'DD-dedupe'
										}
									},{
										title: {
											text: 'DD-compressed'
										}
									},{
										title: {
											text: 'Cumulative'
										}
									},{
										title: {
											text: 'Ratio'
										}
									}],
									rows: [{
										id: 2,
										cells: [{
											id: 'TD',
											format: '0,0[.]00',
											formula: '"TA1"',
											content: '0'
										},{
											id: 'TE',
											format: '0,0[.]00',
											formula: '"TD2 * ( 1 - TF1 )"',
											content: '0'
										},{
											id: 'TF',
											format: '0,0[.]00',
											formula: '"TE2 * 0.5"',
											content: '0'
										},{
											id: 'TG',
											format: '0,0[.]00',
											formula: '"TF2"',
											content: '0'
										},{
											id: 'TH',
											format: '0,0[.]00',
											formula: '"TC2 / TG2"',
											content: '0'
										}]
									},{
										repeat: 100,
										id: 3,
										cells: [{
											id: 'TD',
											format: '0,0[.]00',
											formula: '"IF( TB" + ( id ) + " = 0, 0, IF( TB" + ( id ) + " = TA1, TA1 * TG1, TA1 * TB1 ) )"',
											content: '0'
										},{
											id: 'TE',
											format: '0,0[.]00',
											formula: '"TD" + ( id ) + " * ( 1 - TF1 )"',
											content: '0'
										},{
											id: 'TF',
											format: '0,0[.]00',
											formula: '"TE" + ( id ) + " * 0.5"',
											content: '0'
										},{
											id: 'TG',
											format: '0,0[.]00',
											formula: '"TF" + ( id ) + " + TG" + ( id - 1 )',
											content: '0'
										},{
											id: 'TH',
											format: '0,0[.]00',
											formula: '"TC" + ( id ) + " / TG" + ( id )',
											content: '0'
										}]
									}]
								}]
							}]
						}]
					},{			
						type: 'holder',
						classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
						elements: [{
							type: 'holder',
							elements: [{						
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type:	'text',
									classes: ['smooth-scroll'],
									text: '<h3 class="section-header" style="font-size: 24px;">Nimble (inc baseline copy)</h3>'
								},{
									type: 'rowtable',
									specs: {
										pagination: true
									},
									headers: [{
										title: {
											text: 'Nimble'
										}
									},{
										title: {
											text: 'Nimble Compressed'
										}
									},{
										title: {
											text: 'Cumulative'
										}
									},{
										title: {
											text: 'Ratio'
										}
									}],
									rows: [{
										id: 2,
										cells: [{
											id: 'TI',
											format: '0,0[.]00',
											formula: '"TD2"',
											content: '0'
										},{
											id: 'TJ',
											format: '0,0[.]00',
											formula: '"TI2 * 0.5"',
											content: '0'
										},{
											id: 'TK',
											format: '0,0[.]00',
											formula: '"TJ2"',
											content: '0'
										},{
											id: 'TL',
											format: '0,0[.]00',
											formula: '"TC2 / TK2"',
											content: '0'
										}]
									},{
										repeat: 100,
										id: 3,
										cells: [{
											id: 'TI',
											format: '0,0[.]00',
											formula: '"TD" + ( id )',
											content: '0'
										},{
											id: 'TJ',
											format: '0,0[.]00',
											formula: '"TI" + ( id ) + " * 0.5"',
											content: '0'
										},{
											id: 'TK',
											format: '0,0[.]00',
											formula: '"TJ" + ( id ) + " + TK" + ( id - 1 )',
											content: '0'
										},{
											id: 'TL',
											format: '0,0[.]00',
											formula: '"TC" + ( id ) + " / TK" + ( id )',
											content: '0'
										}]
									}]
								}]
							}]
						}]
					},{			
						type: 'holder',
						classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-2'], 
						elements: [{
							type: 'holder',
							elements: [{						
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type:	'text',
									classes: ['smooth-scroll'],
									text: '<h3 class="section-header" style="font-size: 24px;">Nimble (excl baseline copy)</h3>'
								},{
									type: 'rowtable',
									specs: {
										pagination: true
									},
									headers: [{
										title: {
											text: 'Nimble Excl Baseline'
										}
									},{
										title: {
											text: 'Ratio'
										}
									}],
									rows: [{
										id: 2,
										cells: [{
											id: 'TM',
											format: '0,0[.]00',
											formula: '0',
											content: '0'
										},{
											id: 'TN',
											format: '0,0[.]00',
											formula: '0',
											content: '0'
										}]
									},{
										id: 3,
										repeat: 100,
										cells: [{
											id: 'TM',
											format: '0,0[.]00',
											formula: '"TJ" + ( id ) + " + TM" + ( id - 1 )',
											content: '0'
										},{
											id: 'TN',
											format: '0,0.00',
											formula: '"TC" + ( id ) + " / TM" + ( id )',
											content: '0'
										}]
									}]
								}]
							}]
						}]
					}]
				}]
			}]
		},{
			type: 'section',
			id: 4,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				text: '<h1 style="margin-bottom: 20px;">Details</h1>'
			},
			permission: [{
				level: '2',
				visibility: 'hidden'
			}]
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			permission: [{
				level: '2',
				visibility: 'hidden'
			}],
			elements: [{
				type: 'holder',
				classes: ['row','margin-bottom-25'],
				elements: [{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type: 'table',
								columns: [{
									title: {
										text: 'Nimble Solution'
									},
									rows: [{
										content: 'Primary Data'
									},{
										content: 'Backup Data'
									},{
										content: 'Total'
									},{
										content: 'Primary Nimble'
									},{
										content: 'DR Nimble'
									}]
								},{
									title: {
										text: 'Capacity'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TO1',
											format: '0,0[.]00',
											formula: 'A1 / 2'
										}
									},{
										content: '0',
										cell: {
											id: 'TO2',
											format: '0,0[.]00',
											formula: 'A1 / 100 * VLOOKUP( A2 , TA2:TM1000, 13, FALSE )'
										}
									},{
										content: '0',
										cell: {
											id: 'TO3',
											format: '0,0[.]00',
											formula: 'TO1 + TO2'
										}
									},{
										content: '0',
										cell: {
											id: 'TO4',
											format: '0,0[.]00',
											formula: 'TO3'
										}
									},{
										content: '0',
										cell: {
											id: 'TO5',
											format: '0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, TO4 )'
										}
									}]
								},{
									title: {
										text: 'Arrays'
									},
									rows: [{
										content: ''
									},{
										content: ''
									},{
										content: ''
									},{
										content: '0',
										cell: {
											id: 'TP1',
											format: '0,0[.]00',
											formula: 'IF( TO4, 1, 0 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TP2',
											format: '0,0[.]00',
											formula: 'IF( TO5, 1, 0 )'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},
									rows: [{
										content: ''
									},{
										content: ''
									},{
										content: ''
									},{
										content: '0',
										cell: {
											id: 'TQ1',
											format: '0,0[.]00',
											formula: 'IF( TO4 < TAA1, 0, INT( ( TO4 - TAA1 ) / TAB1 ) + 1 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TQ2',
											format: '0,0[.]00',
											formula: 'IF( TO5 < TAC1, 0, INT( ( TO5 - TAC1 ) / TAD1 ) + 1 )'
										}
									}]
								},{
									title: {
										text: 'Unit Price'
									},
									rows: [{
										content: ''
									},{
										content: ''
									},{
										content: ''
									},{
										content: '0',
										cell: {
											id: 'TR1',
											format: '$0,0[.]00',
											formula: 'TP1 * TAA3 + TQ1 * TAB3'
										}
									},{
										content: '0',
										cell: {
											id: 'TR2',
											format: '$0,0[.]00',
											formula: 'TP2 * TAH3 + TQ2 * TAI3'
										}
									}]
								},{
									title: {
										text: 'Support'
									},
									rows: [{
										content: ''
									},{
										content: ''
									},{
										content: ''
									},{
										content: '0',
										cell: {
											id: 'TS1',
											format: '$0,0[.]00',
											formula: 'TP1 * TAA6 + TQ1 * TAB6'
										}
									},{
										content: '0',
										cell: {
											id: 'TS2',
											format: '$0,0[.]00',
											formula: 'TP2 * TAH6 + TQ2 * TAI6'
										}
									}]
								},{
									title: {
										text: 'Power/Cooling'
									},
									rows: [{
										content: ''
									},{
										content: ''
									},{
										content: ''
									},{
										content: '0',
										cell: {
											id: 'TT1',
											format: '$0,0[.]00',
											formula: 'SUM( A11:A12 ) * TAA4 * TP1 * 24 * 365 / 1000'
										}
									},{
										content: '0',
										cell: {
											id: 'TT2',
											format: '$0,0[.]00',
											formula: 'SUM( A11:A12 ) * TAB4 * TP2 * 24 * 365 / 1000'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: 'Alternate Solution'
									},
									rows: [{
										content: 'Primary iSCSI SAN'
									},{
										content: 'D2D Backup'
									},{
										content: 'Replica'
									}]
								},{
									title: {
										text: 'Row Capacity (TB)'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TU1',
											format: '0,0[.]0',
											formula: 'A1 / ( 1 - TAD5 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TU2',
											format: '0,0[.]0',
											formula: '( A1 / 100 ) * VLOOKUP( A2, TA2:TM1000, 7, FALSE ) / ( 1 - TAE5 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TU3',
											format: '0,0[.]0',
											formula: 'IF( A8 = \'None\', 0, IF( A8 = \'D2D Tier\', TU2 , TU1 ) )'
										}
									}]
								},{
									title: {
										text: 'Arrays'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TV1',
											format: '0,0[.]00',
											formula: 'IF(TU1, 1, 0)'
										}
									},{
										content: '0',
										cell: {
											id: 'TV2',
											format: '0,0[.]00',
											formula: 'IF(TU2, 1, 0)'
										}
									},{
										content: '0',
										cell: {
											id: 'TV3',
											format: '0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, IF( A8 =\'D2D Tier\', TV2 , TV1 ) )'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TW1',
											format: '0,0[.]00',
											formula: 'IF( TU1 < TAC1, 0, INT( ( TU1 - TAC1 ) / TAD1 ) + 1 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TW2',
											format: '0,0[.]00',
											formula: 'IF( TU2 < TAE1, 0, INT( ( TU2 - TAE1 ) / TAF1 ) + 1 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TW3',
											format: '0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, IF( A8 = \'D2D Tier\', TW2 , TW1 ) )'
										}
									}]
								},{
									title: {
										text: 'Unit Price'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TX1',
											format: '$0,0[.]00',
											formula: 'TV1 * TAC3 + TW1 * TAD3'
										}
									},{
										content: '0',
										cell: {
											id: 'TX2',
											format: '$0,0[.]00',
											formula: 'TV2 * TAE3 + TW2 * TAF3'
										}
									},{
										content: '0',
										cell: {
											id: 'TX3',
											format: '$0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, IF( A8 = \'D2D Tier\', TX2 , TX1 ) )'
										}
									}]
								},{
									title: {
										text: 'Support'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TY1',
											format: '$0,0[.]00',
											formula: '2600'
										}
									},{
										content: '0',
										cell: {
											id: 'TY2',
											format: '$0,0[.]00',
											formula: 'TX2 * 0.06'
										}
									},{
										content: '0',
										cell: {
											id: 'TY3',
											format: '$0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, IF( A8 = \'D2D Tier\', TY2 , TY1 ) )'
										}
									}]
								},{
									title: {
										text: 'Power/Cooling'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TZ1',
											format: '$0,0[.]00',
											formula: 'SUM( A11:A12 ) * TV1 * TAD4 * 24 * 365 / 1000'
										}
									},{
										content: '0',
										cell: {
											id: 'TZ2',
											format: '$0,0[.]00',
											formula: 'SUM( A11:A12  ) * ( TV2 * TAE4 + TW2 * TAF4 ) * 24 * 365 / 1000'
										}
									},{
										content: '0',
										cell: {
											id: 'TZ3',
											format: '$0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, IF( A8 = \'D2D Tier\', TZ2 , TZ1 ) )'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Primary + Backup'
									},{
										content: 'DR Replica'
									},{
										content: 'Upgrades'
									},{
										content: 'Opex - Backup Mgmt'
									},{
										content: 'Data loss costs'
									},{
										content: 'Disaster Recovery Costs'
									},{
										content: 'Opex - Power/Cooling'
									},{
										content: 'Professional Services'
									},{
										content: 'Total'
									}]
								},{
									title: {
										text: 'Initial'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAO1',
											format: '$0,0[.]00',
											formula: 'TR1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO2',
											format: '$0,0[.]00',
											formula: 'TR2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO3',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO4',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO5',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO6',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO7',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO8',
											format: '$0,0[.]00',
											formula: '6000'
										}
									},{
										content: '0',
										cell: {
											id: 'TAO9',
											format: '$0,0[.]00',
											formula: 'SUM( TAO1:TAU8 )'
										}
									}]
								},{
									title: {
										text: 'Year 1'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAP1',
											format: '$0,0[.]00',
											formula: 'TS1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP2',
											format: '$0,0[.]00',
											formula: 'IF( A8 = \'None\', 0, TS2 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP3',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP4',
											format: '$0,0[.]00',
											formula: 'A3 * A4 * 52 * ( 1 - A5 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP5',
											format: '$0,0[.]00',
											formula: 'A6 * A7 * TAA7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP6',
											format: '$0,0[.]00',
											formula: 'A10 * 3 * ( A9 * IF( A8 = \'None\', TAG3 ,TAA9 ) + A6 * IF( A8 = \'None\', TAG2, TAA8 ) )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP7',
											format: '$0,0[.]00',
											formula: 'SUM( TT1:TT2 ) * IF( A8 = \'None\', 1, 2 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP8',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAP9',
											format: '$0,0[.]00',
											formula: '0'
										}
									}]
								},{
									title: {
										text: 'Year 2'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAQ1',
											format: '$0,0[.]00',
											formula: 'TAP1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ2',
											format: '$0,0[.]00',
											formula: 'TAP2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ3',
											format: '$0,0[.]00',
											formula: 'TAP3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ4',
											format: '$0,0[.]00',
											formula: 'TAP4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ5',
											format: '$0,0[.]00',
											formula: 'TAP5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ6',
											format: '$0,0[.]00',
											formula: 'TAP6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ7',
											format: '$0,0[.]00',
											formula: 'TAP7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ8',
											format: '$0,0[.]00',
											formula: 'TAP8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAQ9',
											format: '$0,0[.]00',
											formula: 'TAP9'
										}
									}]
								},{
									title: {
										text: 'Year 3'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAR1',
											format: '$0,0[.]00',
											formula: 'TAP1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR2',
											format: '$0,0[.]00',
											formula: 'TAP2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR3',
											format: '$0,0[.]00',
											formula: 'TAP3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR4',
											format: '$0,0[.]00',
											formula: 'TAP4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR5',
											format: '$0,0[.]00',
											formula: 'TAP5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR6',
											format: '$0,0[.]00',
											formula: 'TAP6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR7',
											format: '$0,0[.]00',
											formula: 'TAP7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR8',
											format: '$0,0[.]00',
											formula: 'TAP8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAR9',
											format: '$0,0[.]00',
											formula: 'TAP9'
										}
									}]
								},{
									title: {
										text: 'Year 4'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAS1',
											format: '$0,0[.]00',
											formula: 'TAP1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS2',
											format: '$0,0[.]00',
											formula: 'TAP2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS3',
											format: '$0,0[.]00',
											formula: 'TAP3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS4',
											format: '$0,0[.]00',
											formula: 'TAP4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS5',
											format: '$0,0[.]00',
											formula: 'TAP5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS6',
											format: '$0,0[.]00',
											formula: 'TAP6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS7',
											format: '$0,0[.]00',
											formula: 'TAP7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS8',
											format: '$0,0[.]00',
											formula: 'TAP8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAS9',
											format: '$0,0[.]00',
											formula: 'TAP9'
										}
									}]
								},{
									title: {
										text: 'Year 5'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAT1',
											format: '$0,0[.]00',
											formula: 'TAP1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT2',
											format: '$0,0[.]00',
											formula: 'TAP2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT3',
											format: '$0,0[.]00',
											formula: 'TAP3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT4',
											format: '$0,0[.]00',
											formula: 'TAP4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT5',
											format: '$0,0[.]00',
											formula: 'TAP5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT6',
											format: '$0,0[.]00',
											formula: 'TAP6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT7',
											format: '$0,0[.]00',
											formula: 'TAP7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT8',
											format: '$0,0[.]00',
											formula: 'TAP8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAT9',
											format: '$0,0[.]00',
											formula: 'TAP9'
										}
									}]
								},{
									title: {
										text: 'Year 6'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAU1',
											format: '$0,0[.]00',
											formula: 'TAP1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU2',
											format: '$0,0[.]00',
											formula: 'TAP2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU3',
											format: '$0,0[.]00',
											formula: 'TAP3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU4',
											format: '$0,0[.]00',
											formula: 'TAP4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU5',
											format: '$0,0[.]00',
											formula: 'TAP5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU6',
											format: '$0,0[.]00',
											formula: 'TAP6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU7',
											format: '$0,0[.]00',
											formula: 'TAP7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU8',
											format: '$0,0[.]00',
											formula: 'TAP8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAU9',
											format: '$0,0[.]00',
											formula: 'TAP9'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Primary'
									},{
										content: 'Backup'
									},{
										content: 'DR Replica'
									},{
										content: 'Upgrades'
									},{
										content: 'Opex-Backup Mgmt'
									},{
										content: 'Data loss costs'
									},{
										content: 'Disaster Recovery Costs'
									},{
										content: 'Opex - Power/Cooling'
									},{
										content: 'Professional Services'
									},{
										content: 'Total'
									}]
								},{
									title: {
										text: 'Initial'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAV1',
											format: '$0,0[.]00',
											formula: 'TV1 * TX1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV2',
											format: '$0,0[.]00',
											formula: 'TX2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV3',
											format: '$0,0[.]00',
											formula: 'TV3 * TX3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV4',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV5',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV6',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV7',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV8',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV9',
											format: '$0,0[.]00',
											formula: '0.15 * SUM(TAV1:TAV3)'
										}
									},{
										content: '0',
										cell: {
											id: 'TAV10',
											format: '$0,0[.]00',
											formula: 'SUM( TAV1:TBB9 )'
										}
									}]
								},{
									title: {
										text: 'Year 1'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAW1',
											format: '$0,0[.]00',
											formula: 'TV1 * TY1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW2',
											format: '$0,0[.]00',
											formula: 'TV2 * TY2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW3',
											format: '$0,0[.]00',
											formula: 'TV3 * TY3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW4',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW5',
											format: '$0,0[.]00',
											formula: 'A3 * A4 * 52'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW6',
											format: '$0,0[.]00',
											formula: 'A6 * A7 * TAF7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW7',
											format: '$0,0[.]00',
											formula: 'A10 * 3 * ( A9 * IF( A8 = \'None\', TAG3, IF( A8 = \'D2D Tier\', TAF9, TAC8 ) ) + A6 * IF( A8 = \'None\', TAG2, IF( A8 = \'D2D Tier\', TAF8, TAC7 ) ) )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW8',
											format: '$0,0[.]00',
											formula: 'SUM( TZ1:TZ3 )'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW9',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TAW10',
											format: '$0,0[.]00',
											formula: '0'
										}
									}]
								},{
									title: {
										text: 'Year 2'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAX1',
											format: '$0,0[.]00',
											formula: 'TAW1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX2',
											format: '$0,0[.]00',
											formula: 'TAW2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX3',
											format: '$0,0[.]00',
											formula: 'TAW3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX4',
											format: '$0,0[.]00',
											formula: 'TAW4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX5',
											format: '$0,0[.]00',
											formula: 'TAW5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX6',
											format: '$0,0[.]00',
											formula: 'TAW6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX7',
											format: '$0,0[.]00',
											formula: 'TAW7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX8',
											format: '$0,0[.]00',
											formula: 'TAW8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX9',
											format: '$0,0[.]00',
											formula: 'TAW9'
										}
									},{
										content: '0',
										cell: {
											id: 'TAX10',
											format: '$0,0[.]00',
											formula: 'TAW10'
										}
									}]
								},{
									title: {
										text: 'Year 3'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAY1',
											format: '$0,0[.]00',
											formula: 'TAW1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY2',
											format: '$0,0[.]00',
											formula: 'TAW2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY3',
											format: '$0,0[.]00',
											formula: 'TAW3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY4',
											format: '$0,0[.]00',
											formula: 'TAW4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY5',
											format: '$0,0[.]00',
											formula: 'TAW5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY6',
											format: '$0,0[.]00',
											formula: 'TAW6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY7',
											format: '$0,0[.]00',
											formula: 'TAW7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY8',
											format: '$0,0[.]00',
											formula: 'TAW8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY9',
											format: '$0,0[.]00',
											formula: 'TAW9'
										}
									},{
										content: '0',
										cell: {
											id: 'TAY10',
											format: '$0,0[.]00',
											formula: 'TAW10'
										}
									}]
								},{
									title: {
										text: 'Year 4'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TAZ1',
											format: '$0,0[.]00',
											formula: '1.2 * TAW1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ2',
											format: '$0,0[.]00',
											formula: '1.2 * TAW2'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ3',
											format: '$0,0[.]00',
											formula: '1.2 * TAW3'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ4',
											format: '$0,0[.]00',
											formula: 'SUM(TAV1:TAV3)'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ5',
											format: '$0,0[.]00',
											formula: 'TAW5'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ6',
											format: '$0,0[.]00',
											formula: 'TAW6'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ7',
											format: '$0,0[.]00',
											formula: 'TAW7'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ8',
											format: '$0,0[.]00',
											formula: 'TAW8'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ9',
											format: '$0,0[.]00',
											formula: '0.1 * TAZ4'
										}
									},{
										content: '0',
										cell: {
											id: 'TAZ10',
											format: '$0,0[.]00',
											formula: '0'
										}
									}]
								},{
									title: {
										text: 'Year 5'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TBA1',
											format: '$0,0[.]00',
											formula: '1.2 * TAZ1'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA2',
											format: '$0,0[.]00',
											formula: '1.2 * TAZ2'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA3',
											format: '$0,0[.]00',
											formula: '1.2 * TAZ3'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA4',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA5',
											format: '$0,0[.]00',
											formula: 'TAW5'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA6',
											format: '$0,0[.]00',
											formula: 'TAW6'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA7',
											format: '$0,0[.]00',
											formula: 'TAW7'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA8',
											format: '$0,0[.]00',
											formula: 'TAW8'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA9',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TBA10',
											format: '$0,0[.]00',
											formula: '0'
										}
									}]
								},{
									title: {
										text: 'Year 6'
									},
									rows: [{
										content: '0',
										cell: {
											id: 'TBB1',
											format: '$0,0[.]00',
											formula: '1.2 * TBA1'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB2',
											format: '$0,0[.]00',
											formula: '1.2 * TBA2'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB3',
											format: '$0,0[.]00',
											formula: '1.2 * TBA3'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB4',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB5',
											format: '$0,0[.]00',
											formula: 'TAW5'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB6',
											format: '$0,0[.]00',
											formula: 'TAW6'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB7',
											format: '$0,0[.]00',
											formula: 'TAW7'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB8',
											format: '$0,0[.]00',
											formula: 'TAW8'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB9',
											format: '$0,0[.]00',
											formula: '0'
										}
									},{
										content: '0',
										cell: {
											id: 'TBB10',
											format: '$0,0[.]00',
											formula: '0'
										}
									}]
								}]
							}]
						}]
					}]
				}]
			}]
		},{
			type: 'section',
			id: 5,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				text: '<h1 style="margin-bottom: 20px;">Assumptions</h1>'
			},
			permission: [{
				level: '2',
				visibility: 'hidden'
			}]
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			permission: [{
				level: '2',
				visibility: 'hidden'
			}],
			elements: [{
				type: 'holder',
				classes: ['row','margin-bottom-25'],
				elements: [{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Nimble</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Effective Capacity (TB)'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'Rack Space (RU)'
									},{
										content: 'Support ($)'
									}]
								},{
									title: {
										text: 'Base'
									},
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAA1',
											format: '0,0[.]00'
										}
									},{
										content: '90000',
										cell: {
											type: 'input',
											id: 'TAA2',
											format: '0,0[.]00'
										}
									},{
										content: '60000',
										cell: {
											type: 'input',
											id: 'TAA3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAA4',
											format: '0,0[.]00'
										}
									},{
										content: '3',
										cell: {
											type: 'input',
											id: 'TAA5',
											format: '0,0[.]00'
										}
									},{
										content: '2820',
										cell: {
											id: 'TAA6',
											format: '$0,0[.]00',
											formula: '0.06 * TAA3'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAB1',
											format: '0,0[.]00'
										}
									},{
										content: '0',
										cell: {
											type: 'input',
											id: 'TAB2',
											format: '0,0[.]00'
										}
									},{
										content: '45000',
										cell: {
											type: 'input',
											id: 'TAB3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAB4',
											format: '0,0[.]00'
										}
									},{
										content: '3',
										cell: {
											type: 'input',
											id: 'TAB5',
											format: '0,0[.]00'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAB6',
											format: '$0,0[.]00',
											formula: '0.06 * TAB3'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'Nimble DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '0.25',
										cell: {
											type: 'input',
											id: 'TAA7',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAA8',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAA9',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type: 'text',
								text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Alternate SAN</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Row Capacity'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'RAID + Spares O/H'
									},{
										content: 'Support ($)'
									}]
								},{
									title: {
										text: 'Base'
									},
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAC1',
											format: '0,0[.]00'
										}
									},{
										content: '30000',
										cell: {
											type: 'input',
											id: 'TAC2',
											format: '0,0[.]00'
										}
									},{
										content: '75000',
										cell: {
											type: 'input',
											id: 'TAC3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAC4',
											format: '0,0[.]00'
										}
									},{
										content: '25',
										cell: {
											type: 'input',
											id: 'TAC5',
											format: '0,0[.]00%'
										}
									},{
										content: '2820',
										cell: {
											id: 'TAC6',
											format: '$0,0[.]00',
											formula: '0.06 * TAC3'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},									
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAD1',
											format: '0,0[.]00'
										}
									},{
										content: '30000',
										cell: {
											type: 'input',
											id: 'TAD2',
											format: '0,0[.]00'
										}
									},{
										content: '60000',
										cell: {
											type: 'input',
											id: 'TAD3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAD4',
											format: '0,0[.]00'
										}
									},{
										content: '25',
										cell: {
											type: 'input',
											id: 'TAD5',
											format: '0,0[.]00%'
										}
									},{
										content: '0',
										cell: {
											id: 'TAD6',
											format: '$0,0[.]00',
											formula: '0.06 * TAD3'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAC7',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAC8',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">D2D Backup</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Row Capacity'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'RAID + Spares O/H'
									},{
										content: 'Support (% street)'
									}]
								},{
									title: {
										text: 'Base Array'
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAE1',
											format: '0,0[.]00'
										}
									},{
										content: '0',
										cell: {
											type: 'input',
											id: 'TAE2',
											format: '0,0[.]00'
										}
									},{
										content: '50000',
										cell: {
											type: 'input',
											id: 'TAE3',
											format: '$0,0[.]00'
										}
									},{
										content: '360',
										cell: {
											type: 'input',
											id: 'TAE4',
											format: '0,0[.]00'
										}
									},{
										content: '25',
										cell: {
											type: 'input',
											id: 'TAE5',
											format: '0,0[.]00%'
										}
									},{
										content: '10',
										cell: {
											type: 'input',
											id: 'TAE6',
											format: '0,0[.]00%'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},									
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAF1',
											format: '0,0[.]00'
										}
									},{
										content: '0',
										cell: {
											type: 'input',
											id: 'TAF2',
											format: '0,0[.]00'
										}
									},{
										content: '30000',
										cell: {
											type: 'input',
											id: 'TAF3',
											format: '$0,0[.]00'
										}
									},{
										content: '320',
										cell: {
											type: 'input',
											id: 'TAF4',
											format: '0,0[.]00'
										}
									},{
										content: '25',
										cell: {
											type: 'input',
											id: 'TAF5',
											format: '0,0[.]00%'
										}
									},{
										content: '10',
										cell: {
											type: 'input',
											id: 'TAF6',
											format: '0,0[.]00%'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAF7',
											format: '0,0[.]00'
										}
									},{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAF8',
											format: '0,0[.]00'
										}
									},{
										content: '24',
										cell: {
											type: 'input',
											id: 'TAF9',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Tape Backup</h2>'
								},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAG1',
											format: '0,0[.]00'
										}
									},{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAG2',
											format: '0,0[.]00'
										}
									},{
										content: '48',
										cell: {
											type: 'input',
											id: 'TAG3',
											format: '$0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				}]
			},{
				type: 'holder',
				classes: ['row','margin-bottom-25'],
				elements: [{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Nimble</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Effective Capacity (TB)'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'Rack Space (RU)'
									},{
										content: 'Support ($)'
									}]
								},{
									title: {
										text: 'Base'
									},
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAH1',
											format: '0,0[.]00'
										}
									},{
										content: '90000',
										cell: {
											type: 'input',
											id: 'TAH2',
											format: '0,0[.]00'
										}
									},{
										content: '45000',
										cell: {
											type: 'input',
											id: 'TAH3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAH4',
											format: '0,0[.]00'
										}
									},{
										content: '3',
										cell: {
											type: 'input',
											id: 'TAH5',
											format: '0,0[.]00'
										}
									},{
										content: '2820',
										cell: {
											id: 'TAH6',
											format: '$0,0[.]00',
											formula: '0.06 * TAH3'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},
									rows: [{
										content: '23',
										cell: {
											type: 'input',
											id: 'TAI1',
											format: '0,0[.]00'
										}
									},{
										content: '0',
										cell: {
											type: 'input',
											id: 'TAI2',
											format: '0,0[.]00'
										}
									},{
										content: '30000',
										cell: {
											type: 'input',
											id: 'TAI3',
											format: '$0,0[.]00'
										}
									},{
										content: '499.2',
										cell: {
											type: 'input',
											id: 'TAI4',
											format: '0,0[.]00'
										}
									},{
										content: '3',
										cell: {
											type: 'input',
											id: 'TAI5',
											format: '0,0[.]00'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAI6',
											format: '$0,0[.]00',
											formula: '0.06 * TAI3'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'Nimble DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '0.25',
										cell: {
											type: 'input',
											id: 'TAH7',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAH8',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAH9',
											format: '0,0[.]00'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Dedupe Rate'
									},{
										content: 'Compression Rate'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAH10',
											format: '0,0[.]00'
										}
									},{
										content: '2',
										cell: {
											type: 'input',
											id: 'TAH11',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Alternate SAN</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Row Capacity'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'RAID + Spares O/H'
									},{
										content: 'Support ($)'
									}]
								},{
									title: {
										text: 'Base'
									},
									rows: [{
										content: '4740',
										cell: {
											id: 'TAJ1',
											format: '0,0[.]00',
											formula: 'TAC1'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAJ2',
											format: '0,0[.]00',
											formula: 'TAC2'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAJ3',
											format: '$0,0[.]00',
											formula: 'TAC3'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAJ4',
											format: '0,0[.]00',
											formula: 'TAC4'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAJ5',
											format: '0,0[.]00%',
											formula: 'TAC5'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAJ6',
											format: '$0,0[.]00',
											formula: 'TAC6'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},									
									rows: [{
										content: '4740',
										cell: {
											id: 'TAK1',
											format: '0,0[.]00',
											formula: 'TAD1'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAK1',
											format: '0,0[.]00',
											formula: 'TAD2'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAK3',
											format: '$0,0[.]00',
											formula: 'TAD3'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAK4',
											format: '0,0[.]00',
											formula: 'TAD4'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAK5',
											format: '0,0[.]00%',
											formula: 'TAD5'
										}
									},{
										content: '4740',
										cell: {
											id: 'TAK6',
											format: '$0,0[.]00',
											formula: 'TAD6'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAJ7',
											format: '0,0[.]00'
										}
									},{
										content: '1',
										cell: {
											type: 'input',
											id: 'TAJ8',
											format: '0,0[.]00'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Dedupe Rate'
									},{
										content: 'Compression Rate'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '2',
										cell: {
											type: 'input',
											id: 'TAJ9',
											format: '0,0[.]00'
										}
									},{
										content: '2',
										cell: {
											type: 'input',
											id: 'TAJ10',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">D2D Backup</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Row Capacity'
									},{
										content: 'IOPS'
									},{
										content: 'Street Price'
									},{
										content: 'Power (W)'
									},{
										content: 'RAID + Spares O/H'
									},{
										content: 'Support (% street)'
									}]
								},{
									title: {
										text: 'Base Array'
									},
									rows: [{
										content: '12',
										cell: {
											id: 'TAL1',
											format: '0,0[.]00',
											formula: 'TAE1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAL2',
											format: '0,0[.]00',
											formula: 'TAE2'
										}
									},{
										content: '50000',
										cell: {
											id: 'TAL3',
											format: '$0,0[.]00',
											formula: 'TAE3'
										}
									},{
										content: '360',
										cell: {
											id: 'TAL4',
											format: '0,0[.]00',
											formula: 'TAE4'
										}
									},{
										content: '25',
										cell: {
											id: 'TAL5',
											format: '0,0[.]00%',
											formula: 'TAE5'
										}
									},{
										content: '10',
										cell: {
											id: 'TAL6',
											format: '0,0[.]00%',
											formula: 'TAE6'
										}
									}]
								},{
									title: {
										text: 'Expansion'
									},									
									rows: [{
										content: '12',
										cell: {
											id: 'TAM1',
											format: '0,0[.]00',
											formula: 'TAF1'
										}
									},{
										content: '0',
										cell: {
											id: 'TAM2',
											format: '0,0[.]00',
											formula: 'TAF2'
										}
									},{
										content: '30000',
										cell: {
											id: 'TAM3',
											format: '$0,0[.]00',
											formula: 'TAF3'
										}
									},{
										content: '320',
										cell: {
											id: 'TAM4',
											format: '0,0[.]00',
											formula: 'TAF4'
										}
									},{
										content: '25',
										cell: {
											id: 'TAM5',
											format: '0,0[.]00%',
											formula: 'TAF5'
										}
									},{
										content: '10',
										cell: {
											id: 'TAM6',
											format: '0,0[.]00%',
											formula: 'TAF6'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAM7',
											format: '0,0[.]00'
										}
									},{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAM8',
											format: '0,0[.]00'
										}
									},{
										content: '24',
										cell: {
											type: 'input',
											id: 'TAM9',
											format: '0,0[.]00'
										}
									}]
								}]	
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Dedupe Rate'
									},{
										content: 'Compression Rate'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '2',
										cell: {
											type: 'input',
											id: 'TAM10',
											format: '0,0[.]00'
										}
									},{
										content: '2',
										cell: {
											type: 'input',
											id: 'TAM11',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				},{				
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-3'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
									type: 'text',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Tape Backup</h2>'
							},{
								type: 'table',
								columns: [{
									title: {
										text: ''
									},
									rows: [{
										content: 'Backup RPO (hours)'
									},{
										content: 'DR RPO (hours)'
									},{
										content: 'DR RTO (hours)'
									}]
								},{
									title: {
										text: ''
									},
									rows: [{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAN1',
											format: '0,0[.]00'
										}
									},{
										content: '12',
										cell: {
											type: 'input',
											id: 'TAN2',
											format: '0,0[.]00'
										}
									},{
										content: '48',
										cell: {
											type: 'input',
											id: 'TAN3',
											format: '0,0[.]00'
										}
									}]
								}]	
							}]
						}]
					}]
				}]
			}]
		}]
	};
	
	var section_build = '';
	$.each(testInputs.elements, function( index, value ){
		
		var section_content = __getElementType(value);
		if(section_content){
			section_build += section_content;
		}
	});
	
	$('#roiContent').append(section_build);
	
	var leftSidebarBuild = '';
	leftSidebarBuild += __buildLeftSidebar(leftSidebar);
	$('.navbar-default').append(leftSidebarBuild);
	
});