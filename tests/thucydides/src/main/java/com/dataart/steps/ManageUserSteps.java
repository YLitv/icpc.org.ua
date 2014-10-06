package com.dataart.steps;

import junit.framework.Assert;

import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;

import com.dataart.pages.ManageUserPage;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;

public class ManageUserSteps extends ScenarioSteps {

	ManageUserPage manageUser;

	@Step
	public void user_go_to_menu_Coaches() {

		Actions builder = new Actions(getDriver());
		builder.moveToElement(manageUser.usersLink.get(1)).click().perform();
		waitABit(1000);
		manageUser.element(manageUser.usersLink.get(1)).waitUntilVisible();
		waitABit(1000);
		builder.moveToElement(manageUser.coaches).click().perform();
	}

	@Step
	public void user_click_activate_on_the_first_item_from_the_list() {

		waitABit(1000);
		manageUser.click_on_ActivateStatus("myicpctest@gmail.com");

	}

	@Step
	public void user_should_see_status(String status) {

		if (status.equals("Approved")) {
			Assert.assertEquals(status, manageUser.approvedStatus.getText());
		} else {
			Assert.assertEquals(status, manageUser.notapprovedStatus.getText());
		}

	}
	@Step
	public void user_enter_into_search_field(String email){
		manageUser.waitFor(ExpectedConditions.elementToBeClickable(manageUser.emailSearchField));
		manageUser.typeInto(manageUser.emailSearchField, email);
		manageUser.waitFor(ExpectedConditions.textToBePresentInElement(manageUser.emailCell, email));
	}
	@Step
	public void user_should_see_correct_search_result_in_the_table(String email){
		
		
		Assert.assertEquals(email, manageUser.emailCell.getText());
	}
	
	

}
