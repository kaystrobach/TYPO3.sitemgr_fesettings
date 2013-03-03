/***************************************************************
*  Copyright notice
*
*  (c) 2010 Kay Strobach (typo3@kay-strobach.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/*******************************************************************************
 * Register Namespace
 * Initialize some vars 
 ******************************************************************************/ 	
	Ext.ns('TYPO3.Sitemgr.TemplateApp');

/*******************************************************************************
 * Application object
 ******************************************************************************/
	Ext.ComponentMgr.create = Ext.ComponentMgr.create.createInterceptor(function(config, defaultType) {
		var type = config.xtype || defaultType;
		if ( !Ext.ComponentMgr.isRegistered(type))  {
			throw 'xtype ""'+type+'"" is not a registered component';
		}
		return true;
	});
	
	/**
	  * recreate the alias 
	  */
	Ext.create  =  Ext.ComponentMgr.create;
	
	Ext.onReady(function (){
		TYPO3.Sitemgr.FesettingsApp.init();
	});
	
	TYPO3.Sitemgr.FesettingsApp = {
		applyForm: function(definition) {
			Ext.getCmp('sitemgr_fesettings-tab').removeAll();
			Ext.getCmp('sitemgr_fesettings-tab').add(definition);
			Ext.getCmp('sitemgr_fesettings-tab').doLayout();
		},
		loadOptions: function() {
			TYPO3.Sitemgr.FesettingsApp.applyForm(
				{
					html: '<div class="typo3-message message-information">' + TYPO3.lang.SitemgFesettings_loading + '<div class="message-body"></div></div>',
				}
			);
			Ext.getCmp('sitemgr_fesettings-tab').getTopToolbar().disable();
			TYPO3.sitemgr.tabs.dispatch(
				'sitemgr_fesettings',
				'getOptions',
				{uid: TYPO3.settings.sitemgr.uid},
				function(provider,response) {
					Ext.getCmp('sitemgr_fesettings-tab').getTopToolbar().enable();
					TYPO3.Sitemgr.FesettingsApp.applyForm(
						{
							xtype:'form',
							layout: 'fit',
							id:'settingsForm',
							api:{
								submit:TYPO3.sitemgr.tabs.handleForm
							},
							border:false,
							paramOrder: 'module,fn,args',
							//fileUpload:true, //needs to be enabled for uploades!
							items: [
								{
									border: false,
									activeTab:0,
									xtype: 'tabpanel',
									defaults: {
										autoScroll: true,
										layout: 'form',
										labelAlign: 'top'
									},
									enableTabScroll: true,
									items: response.result.form
								}
							]
						}
					);
				}
			);
		},
		init: function() {
			this.tab = Ext.getCmp('Sitemgr_App_Tabs').add({
				title:TYPO3.lang.SitemgrFesettings_title,
				disabled:!TYPO3.settings.sitemgr.customerSelected,
				id: 'sitemgr_fesettings-tab',
				layoutConfig: {
					border:false
				},
				iconCls: 'fesettings-tab-icon',
				listeners: {
					afterrender: function() {
						TYPO3.Sitemgr.FesettingsApp.loadOptions();
					},
					show: function() {
						Ext.getCmp('sitemgr_fesettings-tab').doLayout();
					}
				},
				layout: 'fit',
				tbar: [
					{
						tooltip:TYPO3.lang.SitemgrFesettings_action_save,
						iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-save',
						handler:function() {
							form = Ext.getCmp('settingsForm').getForm();
							form.submit({
								waitMsg: TYPO3.lang.SitemgrTemplates_theme_apply,
								params: {
									module:'sitemgr_fesettings',
									fn    :'setOptions',
									args  : TYPO3.settings.sitemgr.uid + ';' + TYPO3.settings.sitemgr.uid
								}
							});
						},
						scope:this
					},'-', {
						tooltip:TYPO3.lang.SitemgrFesettings_action_preview,
						iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-view',
						handler:function() {
							windowName = 'previewForId-' + TYPO3.settings.sitemgr.customerRootPid;
							uri        = '../?id=' + TYPO3.settings.sitemgr.customerRootPid;
							if(frames && frames[windowName]) {
								frames[windowName].location.href = uri;
								frames[windowName].focus();
							} else {
								window.open(uri, windowName);
							}
						}
					},'-',{
						tooltip:TYPO3.lang.SitemgrFesettings_action_refresh,
						iconCls: 't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-refresh',
						handler: function() {
							TYPO3.Sitemgr.FesettingsApp.loadOptions();
						}
					}
				],
				items: []
			});
		}
	};