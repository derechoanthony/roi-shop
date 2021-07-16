$(document).ready(function() {
				
	var leftSidebar = {
		
		logo: {
			classes: ['dropdown','profile-element'],
			alt: 'HPE EMC',
			img: 'company_specific_files/424/logo/logo.png'
		},
		categories: [{
			id: '1',
			icon: 'fa-th-large',
			label: 'ROI Sections',
			classes: ['smooth-scroll','active'],
			sections: [{
				id: '1',
				label: 'Dashboard',
				href: '#section1'
			},{
				id: '2',
				label: 'More Reliable Systems',
				href: '#section2'
			},{
				id: '3',
				label: 'IT Productivity',
				href: '#section3'
			},{
				id: '4',
				label: 'Floor Space and Power',
				href: '#section4'
			},{
				id: '5',
				label: 'Cost of Infrastructure',
				href: '#section5'
			},{
				id: '6',
				label: 'Summary',
				href: '#section6'
			}]
		},{
			id: '2',
			icon: 'fa-th-large',
			label: 'References',
			classes: ['smooth-scroll','active'],
			sections: [{
				id: '7',
				label: 'FAQs',
				href: '#section7'
			}]
		},{
			id: '3',
			icon: 'fa-th-large',
			label: 'Your PDFs',
			classes: ['smooth-scroll','active'],
			sections: [{
				id: '7',
				label: 'View PDFs',
				href: '#section7',
				action: 'create-pdf'
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
			id: 1,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				text: '<h1 style="margin-bottom: 20px;">ROI Dashboard | 5 Year Projection</h1>'
			}
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			elements: [{
				type: 'holder',
				classes: ['row'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'], 
					elements: [{
						type: 'holder',
						classes: ['ibox','float-e-margins'],
						elements: [{
							type: 'holder',
							classes: ['ibox-content'],
							elements:[{
								type	:	'text',
								text: '<h3 style="font-size: 18px; font-weight: 700;">Select a section below to review your ROI</h3>\
											<p style="font-size: 16px;">\
												To calculate your return on investment, begin with the first section below. The information\
												entered therein will automatically populate corresponding fields in the other sections. You\
												will be able to move from section to section to add and/or adjust values to best reflect your\
												organization and process. To return to this screen, click the ROI Dashboard button to the left.\
											</p>'								
							}]
						}]
					}]					
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
					elements:[{
						type: 'holder',
						classes: ['row','bottom-border','gray-bg'],
						elements:[{
							type: 'holder',
							classes: ['col-xs-3','col-sm-3','col-md-3','col-lg-3'], 
							elements: [{
								type: 'holder',
								classes: ['ibox-content','section-pod'],
								elements: [{
									type	:	'text',
									classes: ['smooth-scroll'],
									tag: 'a',
									link: '#section2',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">More Reliable Systems</h2>'
								},{
									type:	'text',
									text: '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-formula="SECT1">$0</h1>'
								}]
							}]					
						},{
							type: 'holder',
							classes: ['col-xs-3','col-sm-3','col-md-3','col-lg-3'], 
							elements: [{
								type: 'holder',
								classes: ['ibox-content','section-pod'],
								elements: [{
									type	:	'text',
									classes: ['smooth-scroll'],
									tag: 'a',
									link: '#section2',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">IT Productivity</h2>'
								},{
									type:	'text',
									text: '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-formula="SECT2">$0</h1>'
								}]
							}]					
						},{
							type: 'holder',
							classes: ['col-xs-3','col-sm-3','col-md-3','col-lg-3'], 
							elements: [{
								type: 'holder',
								classes: ['ibox-content','section-pod'],
								elements: [{
									type	:	'text',
									classes: ['smooth-scroll'],
									tag: 'a',
									link: '#section2',
									text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Summary</h2>'
								},{
									type:	'text',
									text: '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-formula="SECT1 + SECT2 - A42">$0</h1>'
								}]
							}]					
						}]
					}]
				}]
			}]
		},{
			type: 'section',
			id: 2,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'More Reliable Systems',
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
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-7'],
					elements: [{
						type: 'text',
						classes: ['caption-text'],
						tag: 'p',
						text: '<span style="font-size: 18px;">On average, customers interviewed reported <strong>96% less downtime.</strong> They experienced 90% fewer downtime instances and resolved downtime 61% faster since moving to Hardware.<br/><br/>VCE customers indicated that Hardware helped them deliver more reliable services, minimizing business risk and making users more productive.<br/><br/><a style="font-size: 24px;" href="http://www.emc.com/collateral/vce/idc-business-value-whitepaper.pdf" target="_blank">View Case Study</a></span>'
					},{
						type: 'text',
						text: '<hr>'
					},{
						type: 'quotes',
						testimonials: [{
							author: 'IDC',
							testimonial: '<strong>4.4 times faster time to market for new services/products</strong> - Improved IT agility, which reduces the time needed to deliver applications and services and provision datacenter resources'
						},{
							author: 'IDC',
							testimonial: '<strong>41% less time spent keeping the lights on</strong> - Greater business innovation as IT staff spend less time “keeping the lights on” and more time on innovation projects including mobile and analytics'
						},{
							author: 'IDC',
							testimonial: '<strong>4.6 times more applications developed/delivered per year</strong> — Increased performance, driving higher levels of customer services and satisfaction'
						},{
							author: 'IDC',
							testimonial: '<strong> 96% less downtime</strong> - Higher levels of cost-effectiveness, scalability, and reliability in the technology infrastructure'
						},{
							author: 'IDC',
							testimonial: 'Hardware helps us scale easier. For example, we can deploy the VM to support a new service — or onboard a new company — in minutes as opposed to maybe weeks'
						},{
							testimonial: 'IDC calculates that over a five-year period, the organizations interviewed for this study will earn an average of $6.20 for every $1.00 invested in VCE’s Hardware Systems'
						}]
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
					elements: [{
						type: 'video',
						src: '//www.youtube.com/embed/WGO-Xi5gnJs'
					}]
				}]
			},{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-8'],
				elements: [{
					type: 'holder',
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
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
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Worker Productivity by Reducing Downtime Events</h2>'
									}]
								}]
							},{
								type: 'input',
								id:	'1',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Total number of downtime incidents per year'
								},
								format: '0,0[.]00',
								value: '0'
							},{
								type: 'input',
								id:	'2',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Typical duration of a downtime incident'
								},
								format: '0,0[.]00',
								append: 'Hours',
								value: '0'
							},{
								type: 'output',
								id:	'3',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Annual Downtime Hours'
								},
								format: '0,0[.]00',
								append: 'Hours',
								formula: 'A1 * A2'
							},{
								type: 'input',
								id:	'5',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'What is the average burdened salary of the employees affected by downtime events (see <i class="fa fa-question-circle tooltipstered"></i> for more detail)'
								},
								format: '$0,0[.]00',
								popup: {
									text: 'Burdened Salary = Salary + benefits (add an additional 28% for benefits and overhead). For example if the salary is $60,000 the burdened salary would be $76,800<br/><br/><a href=\'http://www.simplyhired.com/\' target=\'_blank\'>Click Here to Look Up National Averages</a>'
								},
								value: '0'
							},{
								type: 'output',
								id:	'6',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Hourly Rate'
								},
								format: '$0,0[.]00',
								popup: {
									text: 'Hourly rate is figured by dividing annual salary by 2,080 working hours in a year (52 weeks * 40 hours / week)'
								},
								formula: 'A5 / 2080'
							},{
								type: 'output',
								id:	'7',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Cost of lost productivity due to downtime events'
								},
								format: '$0,0[.]00',
								formula: 'A3 * A6'
							},{
								type: 'input',
								id:	'8',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Hardware can reduce your downtime events by 96% (see <i class="fa fa-question-circle tooltipstered"></i> for case study)'
								},
								format: '0,0[.]00%',
								popup: {
									text: '<a href=\'https://www.emc.com/en-us/converged-infrastructure/blocks/Hardware.htm#collapse=&tab13=0\'&tab12=0/\' target=\'_blank\'>Learn More About Downtime Reduction</a>'
								},
								value: '96'
							},{
								type: 'output',
								id:	'9',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: '<strong>Productivity Savings From Downtime Reduction</strong>'
								},
								format: '$0,0[.]00',
								formula: 'A7 * A8'
							},{
								type: 'holder',
								classes: ['form-horizontal'],
								elements: [{
									type: 'holder',
									classes: ['form-group'],
									elements: [{
										type: 'text',
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Reduce Customer Churn From Fewer Downtime Events</h2>'
									}]
								}]
							},{
								type: 'dropdown',
								id:	'10',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Do downtime events result in lost customers?'
								},
								options: [
									{
										value: '1',
										text: 'Yes',
										showmap: '#11,#12,#13,#14,#15,#16,#17,#18,#19'
									},{
										value: '0',
										text: 'No'
									}
								]
							},{
								type: 'input',
								id:	'11',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'How many customers do you have'
								},
								format: '0,0[.]00',
								value: '0'
							},{
								type: 'input',
								id:	'12',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label: {
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'What is the average value of a customer (annually)'
								},
								format: '$0,0[.]00',
								popup: {
									text: 'On average, how much does a typical customer pay you annually?'
								},
								value: '0'
							},{
								type: 'output',
								id:	'13',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label: {
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Annual revenue from current customers'
								},
								format: '$0,0[.]00',
								formula: 'A11 * A12'
							},{
								type: 'input',
								id:	'14',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'What % of customers leave after each downtime incident'
								},
								format: '0,0[.]00%',
								popup: {
									text: 'Can have an info-graphic or case study to support this input'
								},
								value: '0'
							},{
								type: 'output',
								id:	'15',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Number of customers lost to each downtime incident'
								},
								format: '0,0[.]00',
								formula: 'A11 * A14'
							},{
								type: 'output',
								id:	'16',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Total customers lost from all downtime incidents'
								},
								format: '0,0[.]00',
								formula: 'A1 * A15'
							},{
								type: 'output',
								id:	'17',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Lost revenue from churn from downtime events'
								},
								format: '$0,0[.]00',
								formula: 'A12 * A16'
							},{
								type: 'input',
								id:	'18',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Hardware can reduce your downtime events by 96% (see <i class="fa fa-question-circle tooltipstered"></i> for case study)'
								},
								format: '0,0[.]00%',
								popup: {
									text: '<a href=\'https://www.emc.com/en-us/converged-infrastructure/blocks/Hardware.htm#collapse=&tab13=0\'&tab12=0/\' target=\'_blank\'>Learn More About Downtime Reduction</a>'
								},
								value: '96'
							},{
								type: 'output',
								id:	'19',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: '<strong>Annual Revenue Saved</strong>'
								},
								format: '$0,0[.]00',
								formula: 'A17 * A18'
							},{
								type: 'holder',
								elements: [{
									type: 'text',
									text: '<hr>'
								},{
									type: 'textarea',
									id:	'20',
									classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-10'],
									label	:	{
										classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-2'],
										text: 'Notes'
									},
									rows: '5'
								}]
							},{
								type: 'text',
								text: '<div class="well" style="font-size: 18px"><p style="margin: 0;"><strong><a style="font-size:24px;" href=\'https://www.emc.com/en-us/converged-infrastructure/blocks/Hardware.htm#collapse=&tab13=0\'&tab12=0/\' target=\'_blank\'>Click here</a></strong> to learn how VCE Block Systems can reduce down time by 96% </p></div>'
							}]
						}]
					}]
				}]
			},{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
				elements: [{
					type: 'holder',
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type: 'holder',
								classes: ['row'],
								elements: [{
									type: 'holder',
									classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
									elements: [{
										type: 'holder',
										classes: ['row'],
										elements: [{
											type: 'text',
											tag: 'h2',
											classes: ['section-header'],
											text: 'More Reliable Systems'
										},{
											type: 'text',
											text: '<div class="table-responsive m-t">\
														<table class="table invoice-table">\
															<tbody>\
																<tr>\
																	<td>Year 1</td>\
																	<td data-format="($0,0)" data-formula="( A9 + A19 ) * ( 1 - A21 ) * IF( A23 > 12, 0, ( 12 - A23 ) / 12 )" data-cell="ST1">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 2</td>\
																	<td data-format="($0,0)" data-formula="( A9 + A19 ) * ( 1 - A21 ) * IF( A23 >= 24, 0, IF( A23 <= 12, 1, ( 24 - A23 ) / 12 ) )" data-cell="ST2">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 3</td>\
																	<td data-format="($0,0)" data-formula="( A9 + A19 ) * ( 1 - A21 ) * IF( A23 >= 36, 0, IF( A23 <= 24, 1, ( 36 - A23 ) / 12 ) )" data-cell="ST3">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 4</td>\
																	<td data-format="($0,0)" data-formula="( A9 + A19 ) * ( 1 - A21 ) * IF( A23 >= 48, 0, IF( A23 <= 36, 1, ( 48 - A23 ) / 12 ) )" data-cell="ST4">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 5</td>\
																	<td data-format="($0,0)" data-formula="( A9 + A19 ) * ( 1 - A21 ) * IF( A23 >= 60, 0, IF( A23 <= 48, 1, ( 60 - A23 ) / 12 ) )" data-cell="ST5">$0</td>\
																</tr>\
																<tr>\
																	<td><strong>More Reliable Systems Total Savings</strong></td>\
																	<td><strong><span data-format="($0,0)" data-formula="ST1 + ST2 + ST3 + ST4 + ST5" data-cell="SECT1">$0</span></strong></td>\
																</tr>\
															</tbody>\
														</table>\
													</div>'
										},{
											type: 'slider',
											style: 'stacked',
											label: {
												text: 'Conservative Factor'
											},
											format: '0,0[.]00%',
											id: '21',
											restraints: {
												min: 0,
												max: 100,
												step: 5
											}
										},{
											type: 'toggle',
											classes: ['btn','btn-block','btn-primary','btn-toggle','btn-include'],
											id: '22',
											restraints: {
												onvalue: 1,
												offvalue: 0,
												ontext: "<i class='fa fa-check'> Included</i>",
												offtext: "<i class='fa fa-times'> Excluded</i>",
												onclass: "btn-include",
												offclass: "btn-danger"
											}
										},{
											type: 'slider',
											style: 'stacked',
											label: {
												text: 'Implementation Period'
											},
											format: '0,0',
											id: '23',
											append: ' months',
											restraints: {
												min: 0,
												max: 36,
												step: 1
											}
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
			id: 3,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'IT Productivity',
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
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-7'],
					elements: [{
						type: 'text',
						classes: ['caption-text'],
						tag: 'p',
						text: '<span style="font-size: 18px;">VCE customers interviewed reported that its Hardware environment is contributing to better business outcomes by making the IT environment more agile and elastic, driving more innovation, and enabling the company to better serve users and customers.<br/><br/>In addition, they are deploying applications in 66% less time than before and releasing new services in 77% less time than with their legacy environments<br/><br/>Just as important as the ability to develop and provision services rapidly is the ability to quickly scale those services up (or down) to meet changing needs. VCE customers told us their application development teams have access to capacity when they need it, and they leverage this infrastructure to reduce application development cycles.</span>'
					},{
						type: 'text',
						text: '<hr>'
					},{
						type: 'quotes',
						testimonials: [{
							author: 'IDC',
							testimonial: '<strong>4.4 times faster time to market for new services/products</strong> - Improved IT agility, which reduces the time needed to deliver applications and services and provision datacenter resources'
						},{
							author: 'IDC',
							testimonial: '<strong>41% less time spent keeping the lights on</strong> - Greater business innovation as IT staff spend less time “keeping the lights on” and more time on innovation projects including mobile and analytics'
						},{
							author: 'IDC',
							testimonial: '<strong>4.6 times more applications developed/delivered per year</strong> — Increased performance, driving higher levels of customer services and satisfaction'
						},{
							author: 'IDC',
							testimonial: '<strong> 96% less downtime</strong> - Higher levels of cost-effectiveness, scalability, and reliability in the technology infrastructure'
						},{
							author: 'IDC',
							testimonial: 'Hardware helps us scale easier. For example, we can deploy the VM to support a new service — or onboard a new company — in minutes as opposed to maybe weeks'
						},{
							testimonial: 'IDC calculates that over a five-year period, the organizations interviewed for this study will earn an average of $6.20 for every $1.00 invested in VCE’s Hardware Systems'
						}]
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
					elements: [{
						type: 'video',
						src: '//www.youtube.com/embed/mTiXmRRH4oY'
					}]
				}]
			},{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-8'],
				elements: [{
					type: 'holder',
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
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
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Productivity Improvements</h2>'
									}]
								}]
							},{
								type: 'input',
								id:	'24',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'How many IT / developers do you employ'
								},
								format: '0,0[.]00',
								value: '0'
							},{
								type: 'output',
								id:	'25',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Annual labor hours for above employees'
								},
								format: '0,0[.]00',
								append: 'Hours',
								popup: {
									text: 'Number of IT developers * 2080 working hours a year. (52 weeks * 40 hours week = 2080 working hours in a year)'
								},
								formula: 'A24 * 2080'
							},{
								type: 'input',
								id:	'26',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'What % of those hours are used to “keep the lights on”'
								},
								format: '0,0[.]00%',
								popup: {
									text: 'IDC found that companies that used to spend 78% of time and budget keeping the lights on'
								},
								value: '0'
							},{
								type: 'output',
								id:	'27',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Annual hours spent keeping the lights on'
								},
								format: '0,0[.]00',
								append: 'Hours',
								formula: 'A25 * A26'
							},{
								type: 'input',
								id:	'28',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Hardware can reduce that time by <a href=\'http://www.vce.com/landing/tce\' target=\'_blank\'>41%</a>'
								},
								format: '0,0[.]00%',
								popup: {
									text: '<a href=\'http://www.vce.com/landing/tce\' target=\'_blank\'>Read Case Study</a>'
								},
								value: '41'
							},{
								type: 'output',
								id:	'29',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Hours saved with the use of Hardware'
								},
								format: '0,0[.]00',
								formula: 'A27 * A28',
								append: 'Hours'
							},{
								type: 'output',
								id:	'30',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Equivalent number of full time employees'
								},
								format: '0,0[.]00',
								formula: 'A29 / 2080',
								append: 'Employees'
							},{
								type: 'dropdown',
								id:	'31',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'With the reduction of hours needed to "keep the lights on" how would you utilize this benefit?'
								},
								options: [
									{
										value: 'Hardcount Reduction',
										text: 'Hardcount Reduction'
									},{
										value: 'Time to Market',
										text: 'Time to Market'
									}
								]
							},{
								type: 'holder',
								elements:[{
									type: 'text',
									text: '<hr>'
								},{
									type: 'textarea',
									id:	'32',
									classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-10'],
									label	:	{
										classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-2'],
										text: 'Notes'
									},
									rows: '5'
								}]
							},{
								type: 'holder',
								classes: ['form-horizontal'],
								elements: [{
									type: 'holder',
									classes: ['form-group'],
									elements: [{
										type: 'text',
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Headcount Reduction</h2>'
									}]
								}]
							},{
								type: 'input',
								id:	'33',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Average burden salary of an IT employee'
								},
								format: '$0,0[.]00',
								popup: {
									text: 'Burdened Salary = Salary + benefits (add an additional 28% for benefits and overhead). For example if the salary is $60,000 the burdened salary would be $76,800<br/><br/><a href=\'http://www.simplyhired.com/\' target=\'_blank\'>Click Here to look up National Averages</a>'
								},
								value: '0'
							},{
								type: 'input',
								id:	'34',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Headcount Reduction'
								},
								append: 'Employees',
								format: '0,0[.]00',
								value: '0'
							},{
								type: 'output',
								id:	'35',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label: {
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Average Salary Savings'
								},
								format: '$0,0[.]00',
								formula: 'A33 * A34'
							},{
								type: 'holder',
								classes: ['form-horizontal'],
								elements: [{
									type: 'holder',
									classes: ['form-group'],
									elements: [{
										type: 'text',
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Business Agility KPIs</h2>'
									}]
								}]
							},{
								type: 'rowtable',
								specs: {
									pagination: false,
									searching: false,
									info: false,
									ordering: false
								},
								headers: [{
									header: [{
										title: {
											text: ''
										}
									},{
										title: {
											text: 'Before Hardware'
										}
									},{
										title: {
											text: 'With Hardware'
										}
									},{
										title: {
											text: 'Benefit'
										}
									},{
										title: {
											text: 'Advantage (%)'
										}
									}]
								}],
								rows: [{
									id: 1,
									cells: [{
										content: 'Time to provision server (days)'
									},{
										id: 'TA',
										type: 'input',
										format: '0,0.0',
										content: '7.1'
									},{
										id: 'TB',
										type: 'input',
										format: '0,0.0',
										content: '1.1'
									},{
										id: 'TC',
										format: '0,0.0',
										formula: '"TA1 - TB1"'
									},{
										id: 'TD',
										format: '0,0%',
										formula: '"TC1 / TA1"'
									}]
								},{
									id: 2,
									cells: [{
										content: 'Time to deploy application (weeks)'
									},{
										id: 'TA',
										type: 'input',
										format: '0,0.0',
										content: '4.6'
									},{
										id: 'TB',
										type: 'input',
										format: '0,0.0',
										content: '1.6'
									},{
										id: 'TC',
										format: '0,0.0',
										formula: '"TA2 - TB2"'
									},{
										id: 'TD',
										format: '0,0%',
										formula: '"TC2 / TA2"'
									}]
								},{
									id: 3,
									cells: [{
										content: 'Time for application development life cycle (weeks)'
									},{
										id: 'TA',
										type: 'input',
										format: '0,0.0',
										content: '40.0'
									},{
										id: 'TB',
										type: 'input',
										format: '0,0.0',
										content: '18.1'
									},{
										id: 'TC',
										format: '0,0.0',
										formula: '"TA3 - TB3"'
									},{
										id: 'TD',
										format: '0,0%',
										formula: '"TC3 / TA3"'
									}]
								},{
									id: 4,
									cells: [{
										content: 'Time to market for new services/products (days)'
									},{
										id: 'TA',
										type: 'input',
										format: '0,0.0',
										content: '41.8'
									},{
										id: 'TB',
										type: 'input',
										format: '0,0.0',
										content: '9.5'
									},{
										id: 'TC',
										format: '0,0.0',
										formula: '"TA4 - TB4"'
									},{
										id: 'TD',
										format: '0,0%',
										formula: '"TC4 / TA4"'
									}]
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
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type: 'holder',
								classes: ['row'],
								elements: [{
									type: 'holder',
									classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
									elements: [{
										type: 'holder',
										classes: ['row'],
										elements: [{
											type: 'text',
											tag: 'h2',
											classes: ['section-header'],
											text: 'IT Productivity'
										},{
											type: 'text',
											text: '<div class="table-responsive m-t">\
														<table class="table invoice-table">\
															<tbody>\
																<tr>\
																	<td>Year 1</td>\
																	<td data-format="($0,0)" data-formula="( A35 ) * ( 1 - A39 ) * IF( A23 > 12, 0, ( 12 - A23 ) / 12 )" data-cell="ST6">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 2</td>\
																	<td data-format="($0,0)" data-formula="( A35 ) * ( 1 - A39 ) * IF( A23 >= 24, 0, IF( A23 <= 12, 1, ( 24 - A23 ) / 12 ) )" data-cell="ST7">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 3</td>\
																	<td data-format="($0,0)" data-formula="( A35 ) * ( 1 - A39 ) * IF( A23 >= 36, 0, IF( A23 <= 24, 1, ( 36 - A23 ) / 12 ) )" data-cell="ST8">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 4</td>\
																	<td data-format="($0,0)" data-formula="( A35 ) * ( 1 - A39 ) * IF( A23 >= 48, 0, IF( A23 <= 36, 1, ( 48 - A23 ) / 12 ) )" data-cell="ST9">$0</td>\
																</tr>\
																<tr>\
																	<td>Year 5</td>\
																	<td data-format="($0,0)" data-formula="( A35 ) * ( 1 - A39 ) * IF( A23 >= 60, 0, IF( A23 <= 48, 1, ( 60 - A23 ) / 12 ) )" data-cell="ST10">$0</td>\
																</tr>\
																<tr>\
																	<td><strong>More Reliable Systems Total Savings</strong></td>\
																	<td><strong><span data-format="($0,0)" data-formula="ST6 + ST7 + ST8 + ST9 + ST10" data-cell="SECT2">$0</span></strong></td>\
																</tr>\
															</tbody>\
														</table>\
													</div>'
										},{
											type: 'slider',
											style: 'stacked',
											label: {
												text: 'Conservative Factor'
											},
											format: '0,0[.]00%',
											id: '39',
											restraints: {
												min: 0,
												max: 100,
												step: 5
											}
										},{
											type: 'toggle',
											classes: ['btn','btn-block','btn-primary','btn-toggle','btn-include'],
											id: '40',
											restraints: {
												onvalue: 1,
												offvalue: 0,
												ontext: "<i class='fa fa-check'> Included</i>",
												offtext: "<i class='fa fa-times'> Excluded</i>",
												onclass: "btn-include",
												offclass: "btn-danger"
											}
										},{
											type: 'slider',
											style: 'stacked',
											label: {
												text: 'Implementation Period'
											},
											format: '0,0',
											id: '23',
											append: ' months',
											restraints: {
												min: 0,
												max: 36,
												step: 1
											}
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
			id: 6,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'Summary',
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
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
					elements: [{
						type: 'text',
						classes: ['caption-text'],
						tag: 'p',
						text: '<span style="font-size: 18px;">The table below represents a summary of the ROI you can expect by category and by year. Note the implementation slider located on the right. You can adjust the ROI output to reflect ramp time as you implement. Also, note the expected Net Present Value, your calculated % return and projected payback period above.<br/><br/>Net Present Value is calculated using a 2% inflation rate. Return on Investment is calculated by dividing the total net profit by the total cost. Payback Period calculates the time in months it takes your savings to equal that of your cost, including the implementation period.</span>'
					}]
				}]
			},{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-7'],
				elements: [{
					type: 'holder',
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
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
										classes: ['subsection-header','underlined'],
										tag: 'div',
										text: '<h2>Costs</h2>'
									}]
								}]
							},{
								type: 'input',
								id:	'42',
								classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
								label	:	{
									classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-7'],
									text: 'Total Investment'
								},
								format: '$0,0[.]00',
								value: '0'
							},{
								type: 'rowtable',
								specs: {
									pagination: false,
									searching: false,
									info: false,
									ordering: false
								},
								headers: [{
									header: [{
										title: {
											text: ''
										}
									},{
										title: {
											text: 'Year 1'
										}
									},{
										title: {
											text: 'Year 2'
										}
									},{
										title: {
											text: 'Year 3'
										}
									},{
										title: {
											text: 'Year 4'
										}
									},{
										title: {
											text: 'Year 5'
										}
									},{
										title: {
											text: 'Year 6'
										}
									}]
								}],
								rows: [{
									id: 1,
									cells: [{
										content: 'More Reliable Systems'
									},{
										id: 'SUMA',
										format: '$0,0',
										formula: '"ST1"'
									},{
										id: 'SUMB',
										format: '$0,0',
										formula: '"ST2"'
									},{
										id: 'SUMC',
										format: '$0,0',
										formula: '"ST3"'
									},{
										id: 'SUMD',
										format: '$0,0',
										formula: '"ST4"'
									},{
										id: 'SUME',
										format: '$0,0',
										formula: '"ST5"'
									},{
										id: 'SUMF',
										format: '$0,0',
										formula: '"SECT1"'
									}]
								},{
									id: 2,
									cells: [{
										content: 'IT Productivity'
									},{
										id: 'SUMA',
										format: '$0,0',
										formula: '"ST6"'
									},{
										id: 'SUMB',
										format: '$0,0',
										formula: '"ST7"'
									},{
										id: 'SUMC',
										format: '$0,0',
										formula: '"ST8"'
									},{
										id: 'SUMD',
										format: '$0,0',
										formula: '"ST9"'
									},{
										id: 'SUME',
										format: '$0,0',
										formula: '"ST10"'
									},{
										id: 'SUMF',
										format: '$0,0',
										formula: '"SECT2"'
									}]
								},{
									id: 3,
									cells: [{
										content: 'Total'
									},{
										id: 'SUMA',
										format: '$0,0',
										formula: '"ST1 + ST6"'
									},{
										id: 'SUMB',
										format: '$0,0',
										formula: '"ST2 + ST7"'
									},{
										id: 'SUMC',
										format: '$0,0',
										formula: '"ST3 + ST8"'
									},{
										id: 'SUMD',
										format: '$0,0',
										formula: '"ST4 + ST9"'
									},{
										id: 'SUME',
										format: '$0,0',
										formula: '"ST5 + ST10"'
									},{
										id: 'SUMF',
										format: '$0,0',
										formula: '"SECT1 + SECT2"'
									}]
								}]							
							},{
								type: 'holder',
								classes: ['ibox-content'],
								elements: [{
									type: 'graph',
									id: '29',
									series: [{
										id: '48325',
										name: 'Reliable Systems',
										data: [{
											id: '130',
											equation: 'ST1'
										},{
											id: '131',
											equation: 'ST2'
										},{
											id: '131',
											equation: 'ST3'
										},{
											id: '131',
											equation: 'ST4'
										},{
											id: '131',
											equation: 'ST5'
										}]
									},{
										id: '48310',
										name: 'IT Productivity',
										data: [{
											id: '130',
											equation: 'ST6'
										},{
											id: '131',
											equation: 'ST7'
										},{
											id: '131',
											equation: 'ST8'
										},{
											id: '131',
											equation: 'ST9'
										},{
											id: '131',
											equation: 'ST10'
										}]
									}]
								}]
							}]
						}]
					}]
				}]
			},{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-5'],
				elements: [{
					type: 'holder',
					classes: ['ibox', 'float-e-margins', 'row'],
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type: 'holder',
								classes: ['row'],
								elements: [{
									type: 'holder',
									classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
									elements: [{
										type: 'holder',
										classes: ['row'],
										elements: [{
											type: 'text',
											tag: 'h2',
											classes: ['section-header'],
											text: 'Five-Year ROI Analysis'
										},{
											type: 'rowtable',
											specs: {
												pagination: false,
												searching: false,
												info: false,
												ordering: false
											},
											headers: [{
												header: [{
													title: {
														text: ''
													}
												},{
													title: {
														text: 'Average per Customer'
													}
												},{
													title: {
														text: 'Average per 100 Users'
													}
												}]
											}],
											rows: [{
												id: 1,
												cells: [{
													content: 'Benefit (discounted)'
												},{
													id: 'ANA',
													format: '$0,0',
													formula: '"SECT1 + SECT2"'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											},{
												id: 2,
												cells: [{
													content: 'Investment (discounted)'
												},{
													id: 'ANA',
													format: '$0,0',
													formula: '"A42"'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											},{
												id: 3,
												cells: [{
													content: 'Net Present Value (NPV)'
												},{
													id: 'ANA',
													format: '$0,0',
													formula: '"NPV( 0.02, SUMA3, SUMB3, SUMC3, SUMD3, SUME3 )"'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											},{
												id: 4,
												cells: [{
													content: 'Return on Investment (ROI)'
												},{
													id: 'ANA',
													format: '0,0%',
													formula: '"ANA3 / ANA2"'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											},{
												id: 5,
												cells: [{
													content: 'Payback Period'
												},{
													id: 'ANA',
													format: '0,0[.]0',
													formula: '"ANA2 / ( ANA3 / 5 ) * 12"',
													append: ' months'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											},{
												id: 6,
												cells: [{
													content: 'Discount Rate'
												},{
													id: 'ANA',
													format: '0,0[.]0%',
													content: '12'
												},{
													id: 'ANB',
													format: '$0,0',
													formula: '"ST2"'
												}]
											}]							
										},{
											type: 'slider',
											style: 'stacked',
											label: {
												text: 'Implementation Period'
											},
											format: '0,0',
											id: '23',
											append: ' months',
											restraints: {
												min: 0,
												max: 36,
												step: 1
											}
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
			id: 7,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'Frequently Asked Questions',
				style: 'margin-bottom: 20px;'
			}
		},{
			type: 'holder',
			classes: ['row'],
			elements: [{
				type: 'holder',
				classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-12'],
				elements: [{
					type: 'holder',
					classes: ['wrapper','wrapper-content'],
					elements: [{
						type: 'holder',
						classes: ['faq-item'],
						elements: [{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-question">1. How do I provide my prospect the link to the ROI?</div>'
								}]
							}]
						},{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-answer"><p>Go to the “My Actions” drop down in the top right hand corner and click on show verification link.  This is the link that you give to your customer.</p></div>'
								}]
							}]
						}]
					},{
						type: 'holder',
						classes: ['faq-item'],
						elements: [{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-question">2.	What is the conservative factor slider bar?</div>'
								}]
							}]
						},{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-answer"><p>This allows the prospect to alter the projected annual benefits.  For example, if the projected benefit is $100,000 and you slide the bar to 50% it will reduce the savings to $50,000.</p></div>'
								}]
							}]
						}]
					},{
						type: 'holder',
						classes: ['faq-item'],
						elements: [{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-question">3.	How is the benefit (discounted) total calculated?</div>'
								}]
							}]
						},{
							type: 'holder',
							classes: ['row'],
							elements: [{
								type: 'holder',
								classes: ['col-md-12'],
								elements: [{
									type: 'text',
									text: '<div class="faq-answer"><p>Benefits (discounted) is calcualted by adding Productivity Savings from Downtime Reduction + Annual Revenue Saved + Annual Salary Savings across the 5-year projection.</p></div>'
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
	console.log(testInputs);
	$('#roiContent').append(section_build);
	
	var leftSidebarBuild = '';
	leftSidebarBuild += __buildLeftSidebar(leftSidebar);
	$('.navbar-default').append(leftSidebarBuild);
	
});